<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">

<section class="container py-4">
    <h2 class="mb-4" style="color: blue">
        <i class="fas fa-calendar-plus me-2"></i>Đặt lịch khám
    </h2>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card h-100">
                <div class="card-header">Thông tin dịch vụ</div>
                <div class="card-body" id="serviceDetails">
                    <div class="text-muted" id="sd_empty">Chưa chọn dịch vụ.</div>
                    <div class="d-none" id="sd_content">
                        <h5 class="card-title mb-2" id="sd_name"></h5>
                        <div class="mb-2"><strong>Giá:</strong> <span id="sd_price"></span></div>
                        <div class="mb-2"><strong>Khoa:</strong> <span id="sd_dept"></span></div>
                        <div><strong>Gói dịch vụ:</strong>
                            <div id="sd_desc" class="mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <!-- ✅ Chỉ cần 1 form -->
            <form id="appointmentForm" method="POST" action="{{ route('vnpay_payment') }}" data-auth="{{ auth()->check() ? '1' : '0' }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @auth
            @if(Auth::user()->patient)
                <input type="hidden" name="patient_id" value="{{ Auth::user()->patient->id ?? ''}}">
            @else
                <div class="alert alert-warning">
                    Tài khoản của bạn chưa được liên kết hồ sơ bệnh nhân.
                </div>
            @endif
        @else
            <div class="alert alert-info">
                Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đặt lịch.
            </div>
        @endauth

        <div class="mb-3">
            <label for="department_id" class="form-label">Khoa</label>
            <select id="department_id" class="form-select" required>
                <option value="">-- Chọn khoa --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="service_id" class="form-label">Dịch vụ trong khoa</label>
            <select name="service_id" id="service_id" class="form-select" required @guest disabled @endguest>
                <option value="">-- Chọn dịch vụ --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" data-department-id="{{ $service->department_id }}" data-price="{{ $service->price }}" data-description="{{ $service->description }}">
                        {{ $service->name }} ({{ number_format($service->price, 0, ',', '.') }} đ)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="doctor_id" class="form-label">Bác sĩ</label>
            <select name="doctor_id" id="doctor_id" class="form-select" required>
                <option value="">-- Chọn bác sĩ --</option>
                @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" data-department-id="{{ $doc->department_id }}" style="display:none;">
                        {{ $doc->user->name }} - {{ $doc->department->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="appointment_date" class="form-label">Ngày khám</label>
            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required onkeydown="return false;">
        </div>

        <div class="mb-3">
            <label for="medical_examination" class="form-label">Ca khám</label>
            <select name="medical_examination" id="medical_examination" class="form-select" required>
                <option value="">-- Chọn ca khám --</option>
                <option value="Ca sáng (07:30 - 11:30)">Ca sáng (07:30 - 11:30)</option>
                <option value="Ca chiều (13:00 - 17:00)">Ca chiều (13:00 - 17:00)</option>
            </select>
        </div>

        <!-- appointment_time ẩn để lưu cùng ca khám -->
        <input type="hidden" name="appointment_time" id="appointment_time" value="">

        <div class="mb-3">
            <label for="note" class="form-label">Ghi chú</label>
            <textarea name="note" id="note" rows="3" class="form-control"></textarea>
        </div>

        @auth
            @if(Auth::user()->patient)
                <button type="submit" class="btn btn-success">Thanh toán VNPay</button>
            @else
                <button type="button" class="btn btn-success" disabled>Thanh toán VNPay</button>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-success">Đăng nhập để thanh toán VNPay</a>
        @endauth
            </form>
        </div>
    </div>

</section>

<script src="{{ asset('js/appointment.js') }}"></script>
