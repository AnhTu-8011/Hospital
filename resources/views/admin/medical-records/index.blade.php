@extends('layouts.admin')

@section('title', 'Quản lý hồ sơ bệnh án')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">QUẢN LÝ HỒ SƠ BỆNH ÁN</p>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-notes-medical me-2 text-primary"></i>Danh sách hồ sơ bệnh án
            </h4>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-search me-2"></i>Tìm kiếm hồ sơ
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.medical-records.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-user text-primary me-2"></i>Tên bệnh nhân
                    </label>
                    <input type="text" name="patient_name" value="{{ request('patient_name') }}" class="form-control rounded-3 border-2" placeholder="Nhập tên bệnh nhân">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-user-md text-primary me-2"></i>Tên bác sĩ
                    </label>
                    <input type="text" name="doctor_name" value="{{ request('doctor_name') }}" class="form-control rounded-3 border-2" placeholder="Nhập tên bác sĩ">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>Ngày khám
                    </label>
                    <input type="date" name="appointment_date" value="{{ request('appointment_date') }}" class="form-control rounded-3 border-2">
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-search me-2"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('admin.medical-records.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                    <i class="fas fa-list me-2"></i>Danh sách hồ sơ bệnh án
                </h6>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                        <th class="text-center fw-semibold py-3" style="width: 120px;">Mã hồ sơ</th>
                        <th class="fw-semibold py-3">Bệnh nhân</th>
                        <th class="fw-semibold py-3">Bác sĩ phụ trách</th>
                        <th class="fw-semibold py-3">Ngày khám</th>
                        <th class="fw-semibold py-3">Chẩn đoán</th>
                        <th class="text-center fw-semibold py-3" style="width: 150px;">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    #{{ str_pad($record->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="fw-semibold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                {{ data_get($record, 'patient.name') ?? data_get($record, 'patient.user.name') ?? '-' }}
                            </td>
                            <td class="text-dark">
                                <i class="fas fa-user-md me-2 text-primary"></i>
                                {{ data_get($record, 'appointment.doctor.user.name', '-') }}
                            </td>
                            <td class="text-muted">
                                @php($apptDate = data_get($record, 'appointment.appointment_date'))
                                @if($apptDate)
                                    {{ \Carbon\Carbon::parse($apptDate)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-muted small">{{ \Illuminate\Support\Str::limit($record->diagnosis, 60) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.medical-records.show', $record) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i>Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có hồ sơ bệnh án nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $records->links() }}
            </div>
        </div>
    </div>
@endsection
