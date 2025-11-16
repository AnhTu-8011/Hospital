@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary mb-0"><i class="fas fa-list me-2"></i> Danh sách loại xét nghiệm</h4>
        <a href="{{ route('admin.test-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm loại xét nghiệm
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên loại</th>
                <th>Khoa</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $i => $type)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $type->name }}</td>
                <td>{{ $type->department->name ?? '---' }}</td>
                <td>{{ $type->description ?? '---' }}</td>
                <td>
                    <a href="{{ route('admin.test-types.edit', $type->id) }}" class="btn btn-sm btn-outline-warning me-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.test-types.destroy', $type->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa loại này?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $types->links() }}
    </div>
</div>
@endsection
