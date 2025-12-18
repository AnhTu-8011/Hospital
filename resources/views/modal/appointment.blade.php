<link rel="stylesheet" href="{{ asset('css/appointment.css') }}">

<section class="container py-4">
    <div class="text-center mb-5">
        <p class="text-uppercase text-primary fw-semibold mb-2" style="letter-spacing: .08em; font-size: 0.85rem;">ĐẶT LỊCH KHÁM</p>
        <h2 class="fw-bold mb-2" style="font-size: clamp(1.8rem, 2.2vw + .6rem, 2.4rem);">
            <i class="fas fa-calendar-plus me-2 text-primary"></i>Đặt lịch khám bệnh
        </h2>
        <p class="text-muted mb-0">Vui lòng điền đầy đủ thông tin để đặt lịch khám</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header border-0 py-3 px-4 text-white" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <h5 class="mb-0 fw-bold">Thông tin dịch vụ</h5>
                    </div>
                </div>
                <div class="card-body p-4 text-white" id="serviceDetails">
                    <div class="text-center py-5" id="sd_empty">
                        <i class="fas fa-stethoscope fa-3x mb-3" style="opacity: 0.5;"></i>
                        <p class="mb-0 text-white-50">Chưa chọn dịch vụ.</p>
                        <small class="text-white-50">Vui lòng chọn dịch vụ ở form bên cạnh</small>
                    </div>
                    <div class="d-none" id="sd_content">
                        <div class="bg-white bg-opacity-20 rounded-3 p-3 mb-3" style="backdrop-filter: blur(10px);">
                            <div class="rounded-3 mb-3 overflow-hidden" style="height: 140px;">
                                <img id="sd_image" src="" alt="Hình dịch vụ" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            </div>
                            <h5 class="card-title mb-0 fw-bold text-white" id="sd_name"></h5>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-tag me-2 text-white-50"></i>
                                <strong class="text-white-50 me-2">Giá:</strong>
                                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fs-6" id="sd_price"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-building me-2 text-white-50"></i>
                                <strong class="text-white-50 me-2">Khoa:</strong>
                                <span class="text-white fw-semibold" id="sd_dept"></span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-start mb-2">
                                <i class="fas fa-file-alt me-2 text-white-50 mt-1"></i>
                                <div class="flex-grow-1">
                                    <strong class="text-white-50 d-block mb-2">Gói dịch vụ:</strong>
                                    <div id="sd_desc" class="text-white bg-white bg-opacity-10 rounded-3 p-3" style="line-height: 1.7;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <!-- ✅ Chỉ cần 1 form -->
            <form id="appointmentForm" method="POST" action="{{ route('vnpay_payment') }}" data-auth="{{ auth()->check() ? '1' : '0' }}">
        @csrf

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="fas fa-clipboard-list me-2"></i>Thông tin đặt lịch
                </h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Vui lòng kiểm tra lại thông tin:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @auth
                    @if(Auth::user()->patient)
                        <input type="hidden" name="patient_id" value="{{ Auth::user()->patient->id ?? ''}}">
                        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <div>
                                    <strong>Xin chào, {{ Auth::user()->name }}!</strong>
                                    <p class="mb-0 small">Bạn đã đăng nhập và sẵn sàng đặt lịch.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <strong>Thông báo:</strong> Tài khoản của bạn chưa được liên kết hồ sơ bệnh nhân.
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info border-0 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 100%);">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            <div>
                                Vui lòng <a href="{{ route('login') }}" class="fw-bold text-primary">đăng nhập</a> để đặt lịch.
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="mb-4">
                    <label for="department_id" class="form-label fw-semibold mb-2">
                        <i class="fas fa-building text-primary me-2"></i>Khoa
                    </label>
                    <select name="department_id" id="department_id" class="form-select form-select-lg rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="service_id" class="form-label fw-semibold mb-2">
                        <i class="fas fa-stethoscope text-primary me-2"></i>Dịch vụ trong khoa
                    </label>
                    <select name="service_id" id="service_id" class="form-select form-select-lg rounded-3 border-2" required @guest disabled @endguest style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <option value="">-- Chọn dịch vụ --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-department-id="{{ $service->department_id }}" data-price="{{ $service->price }}" data-description="{{ $service->description }}" data-image="{{ $service->image ? asset('storage/' . $service->image) : '' }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} ({{ number_format($service->price, 0, ',', '.') }} đ)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="doctor_id" class="form-label fw-semibold mb-2">
                        <i class="fas fa-user-md text-primary me-2"></i>Bác sĩ
                    </label>
                    <select name="doctor_id" id="doctor_id" class="form-select form-select-lg rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <option value="">-- Chọn bác sĩ --</option>
                        @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}" data-department-id="{{ $doc->department_id }}" style="display:none;">
                                {{ $doc->user->name }} - {{ $doc->department->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="appointment_date" class="form-label fw-semibold mb-2">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>Ngày khám
                    </label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control form-control-lg rounded-3 border-2" required onkeydown="return false;" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>

                <div class="mb-4">
                    <label for="medical_examination" class="form-label fw-semibold mb-2">
                        <i class="fas fa-clock text-primary me-2"></i>Ca khám
                    </label>
                    <select name="medical_examination" id="medical_examination" class="form-select form-select-lg rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <option value="">-- Chọn ca khám --</option>
                        <option value="Ca sáng (07:30 - 11:30)">🌅 Ca sáng (07:30 - 11:30)</option>
                        <option value="Ca chiều (13:00 - 17:00)">🌆 Ca chiều (13:00 - 17:00)</option>
                    </select>
                </div>

                <!-- appointment_time ẩn để lưu cùng ca khám -->
                <input type="hidden" name="appointment_time" id="appointment_time" value="">

                <div class="mb-4">
                    <label for="note" class="form-label fw-semibold mb-2">
                        <i class="fas fa-notes-medical text-primary me-2"></i>Ghi chú
                    </label>
                    <textarea name="note" id="note" rows="3" class="form-control rounded-3 border-2" placeholder="Nhập ghi chú (nếu có)..." style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';"></textarea>
                </div>

                <div class="d-grid gap-2 mt-4">
                    @auth
                        @if(Auth::user()->patient)
                            <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán VNPay
                            </button>
                        @else
                            <button type="button" class="btn btn-lg rounded-pill shadow-sm bg-secondary text-white fw-bold" disabled>
                                <i class="fas fa-lock me-2"></i>Thanh toán VNPay (Chưa liên kết hồ sơ)
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập để thanh toán VNPay
                        </a>
                    @endauth
                </div>
            </div>
        </div>
            </form>
        </div>
    </div>

</section>

<script src="{{ asset('js/appointment.js') }}"></script>
