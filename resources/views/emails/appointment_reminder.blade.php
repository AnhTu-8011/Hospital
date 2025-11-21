    <!-- /**
     *
     * Để gửi email nhắc lịch trước 1 ngày, cách chuẩn nhất trong Laravel là:
     * Tạo Mailable cho nội dung email.
     * Tạo Console Command chạy mỗi ngày, quét các lịch hẹn ngày mai và gửi mail.
     * Đăng ký command trong Kernel để Laravel Scheduler gọi tự động.
     */ -->
<p>Chào {{ $appointment->patient->name ?? 'Quý bệnh nhân' }},</p>

<p>Đây là email nhắc lịch khám của bạn tại <strong>Bệnh viện PHÚC AN</strong>:</p>

<ul>
    <li><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</li>
    <li><strong>Ca khám:</strong> {{ $appointment->medical_examination ?? 'Không xác định' }}</li>
    <li><strong>Dịch vụ:</strong> {{ $appointment->service->name ?? '-' }}</li>
    <li><strong>Bác sĩ:</strong> {{ optional(optional($appointment->doctor)->user)->name ?? '-' }}</li>
</ul>

<p>Vui lòng đến sớm 10–15 phút để làm thủ tục.</p>

<p>Trân trọng,<br>Bệnh viện PHÚC AN</p>