<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhắc nhở lịch hẹn</title>
</head>
<body>
    <p>Chào {{ optional($appointment->patient)->name ?? 'Quý khách' }},</p>

    <p>Đây là email nhắc nhở rằng bạn có lịch hẹn vào ngày mai.</p>

    <ul>
        <li><strong>Thời gian:</strong> {{ optional($appointment->appointment_date)->format('d/m/Y H:i') }}</li>
        <li><strong>Bác sĩ:</strong> {{ optional(optional($appointment->doctor)->user)->name }}</li>
        <li><strong>Dịch vụ:</strong> {{ optional($appointment->service)->name }}</li>
        <li><strong>Trạng thái:</strong> {{ $appointment->status }}</li>
    </ul>

    <p>Nếu bạn không thể tham dự, vui lòng liên hệ sớm để được hỗ trợ.</p>

    <p>Trân trọng,<br>Phòng khám</p>
</body>
</html>
