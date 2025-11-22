@extends('home.frontend')

@section('title', 'Đặt lịch khám - Bệnh viện PHÚC AN')

@section('content')
    <section class="py-5 bg-light">
        <div class="container">
            <div class="mb-4 text-center">
                <h1 class="fw-bold text-primary mb-2">Đặt lịch khám</h1>
                <p class="text-muted mb-0">Vui lòng chọn khoa, dịch vụ và bác sĩ phù hợp để đặt lịch khám.</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    @include('modal.appointment')
                </div>
            </div>
        </div>
    </section>
@endsection
