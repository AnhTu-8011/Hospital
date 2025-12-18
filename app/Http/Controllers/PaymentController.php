<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\{Appointment, Service, Doctor};
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
    {
        try {
            Log::info('VNPay Payment Request:', $request->all());

            $validated = $request->validate([
                'patient_id'          => 'required|exists:patients,id',
                'department_id'       => 'nullable|exists:departments,id',
                'doctor_id'           => 'required|exists:doctors,id',
                'service_id'          => 'required|exists:services,id',
                'appointment_date'    => 'required|date',
                'appointment_time'    => 'required',
                'medical_examination' => 'required|in:Ca sáng (07:30 - 11:30),Ca chiều (13:00 - 17:00)',
                'note'                => 'nullable|string',
            ]);

            $service = Service::with('department')->findOrFail($validated['service_id']);
            $doctor = Doctor::findOrFail($validated['doctor_id']);

            // Đồng bộ khoa theo dịch vụ nếu user không gửi khoa (hoặc để validate chéo)
            $departmentId = $validated['department_id'] ?? $service->department_id;

            // Nếu có gửi department_id thì bắt buộc phải khớp với department của dịch vụ
            if (!empty($validated['department_id']) && (string) $validated['department_id'] !== (string) $service->department_id) {
                return back()->with('error', 'Dịch vụ không thuộc khoa đã chọn. Vui lòng chọn lại.')->withInput();
            }

            // Bác sĩ cũng phải thuộc đúng khoa (theo khoa đã chọn/suy ra)
            if ($departmentId && (string) $doctor->department_id !== (string) $departmentId) {
                return back()->with('error', 'Bác sĩ không thuộc khoa của dịch vụ đã chọn. Vui lòng chọn lại.')->withInput();
            }

            DB::beginTransaction();

            $appointmentData = [
                'patient_id'          => $validated['patient_id'],
                'doctor_id'           => $validated['doctor_id'],
                'service_id'          => $validated['service_id'],
                'appointment_date'    => $validated['appointment_date'],
                'medical_examination' => $validated['medical_examination'],
                'payment_status'      => 'failed',
                'note'                => $validated['note'] ?? null,
            ];

            if (Schema::hasColumn('appointments', 'appointment_time')) {
                $appointmentData['appointment_time'] = $validated['appointment_time'];
            }

            if (Schema::hasColumn('appointments', 'total')) {
                $appointmentData['total'] = $service->price;
            }

            $appointment = Appointment::create($appointmentData);

            // Tạo mã giao dịch (TxnRef)
            $txnRef = $this->getTxnRef($appointment->id);
            if (Schema::hasColumn('appointments', 'transaction_ref')) {
                $appointment->update(['transaction_ref' => $txnRef]);
            }

            // Build VNPay URL
            $vnpUrl = $this->buildVnpayUrl($appointment, $service->price, $request, $txnRef);

            DB::commit();

            Log::info('VNPay Redirect URL', ['url' => $vnpUrl]);
            return redirect()->away($vnpUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Lỗi khi khởi tạo thanh toán: ' . $e->getMessage());
        }
    }

    public function vnpay_return(Request $request)
    {
        $input = $request->all();
        Log::info('VNPay Return Data', $input);

        $responseCode = $input['vnp_ResponseCode'] ?? null;
        $orderInfo    = $input['vnp_OrderInfo'] ?? '';

        if (!$responseCode) {
            return redirect()->route('home')->with('error', 'Dữ liệu phản hồi không hợp lệ.');
        }

        if (!preg_match('/#(\d+)/', $orderInfo, $matches)) {
            return redirect()->route('home')->with('error', 'Không thể xác định lịch hẹn.');
        }

        $appointmentId = $matches[1];
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return redirect()->route('home')->with('error', 'Không tìm thấy lịch hẹn.');
        }

        if ($responseCode === '00') {
            $appointment->update([
                'payment_status' => 'success',
                'transaction_id' => $input['vnp_TransactionNo'] ?? null,
                'transaction_ref' => $input['vnp_TxnRef'] ?? null,
                'payment_method' => 'VNPay - ' . ($input['vnp_BankCode'] ?? 'N/A'),
                'paid_at' => now(),
            ]);

            return redirect()
                ->route('appointments.show', $appointment->id)
                ->with('success', 'Thanh toán thành công!');
        }

        $errorMessage = $responseCode === '24'
            ? 'Giao dịch đã bị hủy.'
            : 'Thanh toán thất bại. Mã lỗi: ' . $responseCode;

        $appointment->update(['payment_status' => 'failed']);
        return redirect()->route('appointments.show', $appointment->id)->with('error', $errorMessage);
    }

    /**
     * Khởi tạo thanh toán cho một lịch hẹn đã tồn tại (từ trang chi tiết lịch hẹn)
     */
    public function checkout(Request $request, Appointment $appointment)
    {
        try {
            if ($appointment->payment_status === 'success') {
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('error', 'Lịch hẹn này đã được thanh toán.');
            }

            // Xác định số tiền cần thanh toán
            $amount = null;
            if (Schema::hasColumn('appointments', 'total') && !is_null($appointment->total)) {
                $amount = (float)$appointment->total;
            } else {
                $service = Service::find($appointment->service_id);
                $amount = $service ? (float)$service->price : 0;
            }

            if ($amount <= 0) {
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('error', 'Không xác định được số tiền thanh toán cho lịch hẹn.');
            }

            // Tạo mã giao dịch và cập nhật lịch hẹn nếu có cột tương ứng
            $txnRef = $this->getTxnRef($appointment->id);
            if (Schema::hasColumn('appointments', 'transaction_ref')) {
                $appointment->update(['transaction_ref' => $txnRef]);
            }
            if (Schema::hasColumn('appointments', 'payment_status')) {
                $appointment->update(['payment_status' => 'failed']);
            }

            // Build URL và chuyển hướng
            $vnpUrl = $this->buildVnpayUrl($appointment, $amount, $request, $txnRef);
            Log::info('VNPay Checkout Redirect URL', ['url' => $vnpUrl]);
            return redirect()->away($vnpUrl);
        } catch (\Exception $e) {
            Log::error('VNPay Checkout Error', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('appointments.show', $appointment->id)
                ->with('error', 'Không thể khởi tạo thanh toán: ' . $e->getMessage());
        }
    }

    private function getTxnRef($appointmentId)
    {
        return 'APP' . $appointmentId . '_' . time();
    }

    /**
     * Build URL theo chuẩn VNPay — KHÔNG sai chữ ký
     */
    private function buildVnpayUrl(Appointment $appointment, $price, Request $request, $txnRef)
    {
        $vnp_TmnCode    = trim(env('VNPAY_TMN_CODE', '4ITGG2O5'));
        $vnp_HashSecret = trim(env('VNPAY_HASH_SECRET', 'AJ45UO1MCUTPO3F1HIMTSVYOESY1GCJ0'));
        $vnp_Url        = rtrim(env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'), '?');
        $vnp_Returnurl  = trim(env('VNPAY_RETURN_URL', route('vnpay.return')));

        // Lấy ngày sinh của bệnh nhân
        $birthdate = $appointment->patient->birthdate ?? null;

        // Mặc định giảm 20%
        $discount = 0.8;

        // Nếu có ngày sinh và tháng sinh trùng với tháng hiện tại → giảm thêm 10%
        if ($birthdate && Carbon::parse($birthdate)->format('m') == now()->format('m')) {
            $discount = 0.7;
        }

        // Tính số tiền thanh toán
        $vnp_Amount = (int)($price * $discount * 100);

        $params = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $request->ip(),
            "vnp_Locale"     => $request->input('language', 'vn'),
            "vnp_OrderInfo"  => "Thanh toan lich kham #" . $appointment->id,
            "vnp_OrderType"  => "billpayment",
            "vnp_ReturnUrl"  => $vnp_Returnurl,
            "vnp_TxnRef"     => $txnRef,
            "vnp_ExpireDate" => now()->addMinutes(15)->format('YmdHis'),
        ];

        if ($bankCode = $request->input('bankCode')) {
            $params['vnp_BankCode'] = $bankCode;
        }

        ksort($params);
        $hashData = '';
        $query = '';
        $i = 0;

        foreach ($params as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $finalUrl = $vnp_Url . '?' . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        Log::debug('VNPay Signature Debug', [
            'hash_data' => $hashData,
            'secure_hash' => $vnpSecureHash,
            'final_url' => $finalUrl,
        ]);

        return $finalUrl;
    }
}
