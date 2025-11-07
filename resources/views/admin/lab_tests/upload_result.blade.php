@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4 class="text-primary">Upload kết quả xét nghiệm: {{ $test->test_name }}</h4>

    <form action="{{ route('admin.lab_tests.saveUpload', $test->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Ảnh chính</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh phụ (nhiều ảnh)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Tải lên</button>
        <a href="{{ route('admin.lab_tests.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
