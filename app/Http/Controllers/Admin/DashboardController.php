<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    // Hiển thị trang Dashboard dành cho Admin với thống kê tổng quan và doanh thu
    public function index()
    {
        // Lấy ngày hiện tại (chỉ phần ngày, không bao gồm giờ)
        $today = Carbon::today();

        // =====================================================
        // 📊 PHẦN 1: THỐNG KÊ CHÍNH TRÊN DASHBOARD
        // =====================================================
        $stats = [
            // Tổng số bác sĩ trong hệ thống
            'total_doctors' => Doctor::count(),

            // Tổng số bệnh nhân (ở đây giả định role_id = 1 là bệnh nhân)
            'total_patients' => User::where('role_id', 1)->count(),

            // Tổng số khoa (phòng ban)
            'total_departments' => Department::count(),

            // Tổng số dịch vụ khám bệnh
            'total_services' => Service::count(),

            // Tổng số lịch hẹn trong toàn hệ thống
            'total_appointments' => Appointment::count(),

            // Số lượng lịch hẹn diễn ra trong ngày hôm nay
            'today_appointments' => Appointment::whereDate('appointment_date', $today)->count(),

            // Số lượng lịch hẹn đang chờ xác nhận
            'pending_appointments' => Appointment::where('status', 'pending')->count(),

            // Số lượng lịch hẹn đã hoàn thành
            'completed_appointments' => Appointment::where('status', 'completed')->count(),

            // Số lượng lịch hẹn đã thanh toán
            'paid_appointments' => Appointment::where('status', 'paid')->count(),
        ];

        // =====================================================
        // 📅 PHẦN 2: DANH SÁCH LỊCH HẸN GẦN NHẤT
        // =====================================================
        $recentAppointments = Appointment::with([
            'patient',      // Quan hệ với bệnh nhân
            'doctor.user',  // Quan hệ với bác sĩ và thông tin user của bác sĩ
            'service',       // Quan hệ với dịch vụ khám bệnh
        ])
            ->orderByDesc('appointment_date') // Sắp xếp lịch hẹn mới nhất lên đầu
            ->limit(10)                       // Giới hạn lấy 10 lịch hẹn gần nhất
            ->get();

        // =====================================================
        // 💰 PHẦN 3: THỐNG KÊ DOANH THU
        // =====================================================
        // Tính mốc thời gian để lọc dữ liệu thống kê
        $start7Days = Carbon::now()->subDays(6)->startOfDay();     // 7 ngày gần nhất
        $start12Months = Carbon::now()->subMonths(11)->startOfMonth(); // 12 tháng gần nhất

        // ===== 💵 Tổng doanh thu toàn hệ thống =====
        // Tính theo số tiền thanh toán thành công thực tế (áp dụng ưu đãi theo tháng sinh)
        $priceExpr = Schema::hasColumn('appointments', 'total') ? 'appointments.total' : 'services.price';
        $dateExpr = Schema::hasColumn('appointments', 'paid_at')
            ? 'COALESCE(appointments.paid_at, appointments.appointment_date)'
            : 'appointments.appointment_date';
        $discountExpr = 'CASE WHEN patients.birthdate IS NOT NULL AND MONTH(patients.birthdate) = MONTH('.$dateExpr.') THEN 0.7 ELSE 0.8 END';

        $totalRevenue = Appointment::where('payment_status', Appointment::PAYMENT_SUCCESS)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->selectRaw('SUM(('.$priceExpr.') * '.$discountExpr.') as total')
            ->value('total');

        // ===== 📈 Doanh thu theo NGÀY (7 ngày gần nhất) =====
        // Nhóm theo ngày thanh toán (paid_at) nếu có, nếu không thì theo ngày hẹn
        $daily = Appointment::where('payment_status', Appointment::PAYMENT_SUCCESS)
            ->whereRaw($dateExpr.' >= ?', [$start7Days])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->selectRaw('DATE('.$dateExpr.') as date, SUM(('.$priceExpr.') * '.$discountExpr.') as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // ===== 📊 Doanh thu theo THÁNG (12 tháng gần nhất) =====
        // Nhóm theo định dạng YYYY-MM của thời điểm thanh toán (paid_at) nếu có
        $monthly = Appointment::where('payment_status', Appointment::PAYMENT_SUCCESS)
            ->whereRaw($dateExpr.' >= ?', [$start12Months])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->selectRaw('DATE_FORMAT('.$dateExpr.', "%Y-%m") as month, SUM(('.$priceExpr.') * '.$discountExpr.') as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // ===== 📆 Doanh thu theo NĂM =====
        // Nhóm theo năm của thời điểm thanh toán (paid_at) nếu có
        $yearly = Appointment::where('payment_status', Appointment::PAYMENT_SUCCESS)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->selectRaw('YEAR('.$dateExpr.') as year, SUM(('.$priceExpr.') * '.$discountExpr.') as total')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // =====================================================
        // 📤 PHẦN 4: TRẢ DỮ LIỆU RA VIEW
        // =====================================================
        // Truyền toàn bộ dữ liệu (thống kê + lịch hẹn + doanh thu)
        // sang view "admin.dashboard.index" để hiển thị biểu đồ & bảng thống kê
        return view('admin.dashboard.index', compact(
            'stats',             // Thống kê tổng quan
            'recentAppointments',// Danh sách lịch hẹn mới nhất
            'totalRevenue',      // Tổng doanh thu
            'daily',             // Doanh thu theo ngày
            'monthly',           // Doanh thu theo tháng
            'yearly'             // Doanh thu theo năm
        ));
    }
}
