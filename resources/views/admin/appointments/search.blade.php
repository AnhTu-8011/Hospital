@section('content')
    {{-- Search Form Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                QUẢN LÝ LỊCH HẸN
            </p>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-calendar-check me-2 text-primary"></i>
                Danh sách lịch hẹn
            </h4>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-search me-2"></i>
                Tìm kiếm lịch hẹn
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-3">
                {{-- Tên bệnh nhân --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-user text-primary me-2"></i>
                        Tên bệnh nhân
                    </label>
                    <input type="text"
                           name="patient_name"
                           value="{{ request('patient_name') }}"
                           placeholder="Nhập tên bệnh nhân"
                           class="form-control rounded-3 border-2"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>

                {{-- Tên bác sĩ --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-user-md text-primary me-2"></i>
                        Tên bác sĩ
                    </label>
                    <input type="text"
                           name="doctor_name"
                           value="{{ request('doctor_name') }}"
                           placeholder="Nhập tên bác sĩ"
                           class="form-control rounded-3 border-2"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>

                {{-- Mã bảo hiểm --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        Mã bảo hiểm
                    </label>
                    <input type="text"
                           name="insurance_number"
                           value="{{ request('insurance_number') }}"
                           placeholder="Nhập mã bảo hiểm"
                           class="form-control rounded-3 border-2"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>

                {{-- Ngày hẹn --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Ngày hẹn
                    </label>
                    <input type="date"
                           name="appointment_date"
                           value="{{ request('appointment_date') }}"
                           class="form-control rounded-3 border-2"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Trạng thái
                    </label>
                    <select name="status"
                            class="form-select rounded-3 border-2"
                            style="transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                            onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã Khám</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã Hủy</option>
                    </select>
                </div>

                {{-- Form Actions --}}
                <div class="col-12 d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-search me-2"></i>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-redo me-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
