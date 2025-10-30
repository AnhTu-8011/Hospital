@extends('layouts.profile')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Cập nhật thông tin cá nhân</h5>
    </div>
    <div class="card-body">
        @include('profile.partials.update-profile-information-form')
        <hr>
        @include('profile.partials.update-password-form')
        <hr>
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
