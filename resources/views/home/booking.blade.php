@extends('home.frontend')

@section('title', 'Đặt lịch khám - Bệnh viện PHÚC AN')

@section('content')
    <section class="py-5 py-lg-6" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">
        <div class="container">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    @include('modal.appointment')
                </div>
            </div>
        </div>
    </section>
@endsection
