@extends('layouts.admin')

@section('title', 'Lịch khám bác sĩ ' . $doctor->user->name)

@section('content')
<div class="card shadow-sm border-0 rounded-4 p-4">
    <h4 class="fw-bold text-primary mb-4">
        🩺 Lịch khám ngày {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}
    </h4>

    <div class="mb-4">
        <p class="fs-5">
            <strong>Bác sĩ:</strong> {{ $doctor->user->name }}<br>
            <strong>Chuyên khoa:</strong> {{ $doctor->department->name ?? '-' }}
        </p>
    </div>

    <div class="alert alert-info fs-6">
        <i class="fas fa-sun me-2 text-warning"></i> 
        <strong>Ca sáng:</strong> {{ $morningCount }}/25 ca đã đặt
    </div>

    <div class="alert alert-info fs-6">
        <i class="fas fa-cloud-sun me-2 text-primary"></i> 
        <strong>Ca chiều:</strong> {{ $afternoonCount }}/25 ca đã đặt
    </div>

    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>
@endsection
