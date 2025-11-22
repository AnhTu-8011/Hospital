@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">QUẢN LÝ NGƯỜI DÙNG</p>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-users me-2 text-primary"></i>Người dùng
            </h4>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>Lọc theo vai trò
            </h6>
        </div>
        <div class="card-body p-4">
            <ul class="nav nav-pills mb-0 gap-2">
                <li class="nav-item">
                    <a class="nav-link rounded-pill {{ request('role') === null ? 'active' : '' }}" 
                       href="{{ route('admin.users.index') }}"
                       style="{{ request('role') === null ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;' : '' }}">
                        <i class="fas fa-users me-1"></i>Tất cả ({{ $counts['all'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill {{ request('role') === 'patient' ? 'active' : '' }}" 
                       href="{{ route('admin.users.index', ['role' => 'patient']) }}"
                       style="{{ request('role') === 'patient' ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;' : '' }}">
                        <i class="fas fa-user-injured me-1"></i>Bệnh nhân ({{ $counts['patient'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill {{ request('role') === 'doctor' ? 'active' : '' }}" 
                       href="{{ route('admin.users.index', ['role' => 'doctor']) }}"
                       style="{{ request('role') === 'doctor' ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;' : '' }}">
                        <i class="fas fa-user-md me-1"></i>Bác sĩ ({{ $counts['doctor'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill {{ request('role') === 'admin' ? 'active' : '' }}" 
                       href="{{ route('admin.users.index', ['role' => 'admin']) }}"
                       style="{{ request('role') === 'admin' ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;' : '' }}">
                        <i class="fas fa-user-shield me-1"></i>Quản trị ({{ $counts['admin'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill {{ request('role') === 'leader' ? 'active' : '' }}" 
                       href="{{ route('admin.users.index', ['role' => 'leader']) }}"
                       style="{{ request('role') === 'leader' ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;' : '' }}">
                        <i class="fas fa-user-tie me-1"></i>Leader ({{ $counts['leader'] }})
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-list me-2"></i>Danh sách người dùng
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                            <th class="text-center fw-semibold py-3" style="width: 120px;">Mã</th>
                            <th class="fw-semibold py-3">Tên</th>
                            <th class="fw-semibold py-3">Email</th>
                            <th class="fw-semibold py-3">Vai trò</th>
                            <th class="fw-semibold py-3">Điện thoại</th>
                            <th class="fw-semibold py-3">Địa chỉ</th>
                            <th class="text-center fw-semibold py-3" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $i => $user)
                        <tr class="table-row-hover" style="transition: all 0.2s ease;">
                            <td class="text-center fw-medium">{{ ($users->currentPage() - 1) * $users->perPage() + $i + 1 }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="fw-semibold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>{{ $user->name }}
                            </td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleName = optional($user->role)->name ?? '-';
                                    $roleColors = [
                                        'admin' => 'danger',
                                        'doctor' => 'info',
                                        'patient' => 'success',
                                        'leader' => 'warning'
                                    ];
                                    $roleColor = $roleColors[$roleName] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $roleColor }}-subtle text-{{ $roleColor }} rounded-pill px-3 py-1">
                                    {{ $roleName }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $user->phone ?? '-' }}</td>
                            <td class="text-muted small">{{ $user->address ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Xóa người dùng này?')" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>

    <style>
    .table-row-hover:hover {
        background-color: #f8f9ff !important;
        transform: scale(1.01);
    }
    .nav-link {
        transition: all 0.3s ease;
    }
    .nav-link:hover:not(.active) {
        background-color: #f0f0f0;
    }
    </style>
@endsection
