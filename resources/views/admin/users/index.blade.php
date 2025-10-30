@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-primary">Người dùng</h4>
</div>

<ul class="nav nav-pills mb-3">
    <li class="nav-item"><a class="nav-link {{ request('role') === null ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Tất cả ({{ $counts['all'] }})</a></li>
    <li class="nav-item"><a class="nav-link {{ request('role') === 'patient' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'patient']) }}">Bệnh nhân ({{ $counts['patient'] }})</a></li>
    <li class="nav-item"><a class="nav-link {{ request('role') === 'doctor' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'doctor']) }}">Bác sĩ ({{ $counts['doctor'] }})</a></li>
    <li class="nav-item"><a class="nav-link {{ request('role') === 'admin' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'admin']) }}">Quản trị ({{ $counts['admin'] }})</a></li>
    <li class="nav-item"><a class="nav-link {{ request('role') === 'leader' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'leader']) }}">Leader ({{ $counts['leader'] }})</a></li>
</ul>

<div class="table-responsive">
<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>STT</th>
            <th>Mã</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Điện thoại</th>
            <th>Địa chỉ</th>
            <th class="text-end">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $i => $user)
        <tr>
            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $i + 1 }}</td>
            <td class="text-muted">#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ optional($user->role)->name ?? '-' }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td>{{ $user->address ?? '-' }}</td>
            <td class="text-end">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa người dùng này?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection
