@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4 class="text-primary"><i class="fas fa-vials me-2"></i> Danh sách xét nghiệm</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên xét nghiệm</th>
                <th>Khoa</th>
                <th>Hồ sơ khám</th>
                <th>Tên bệnh nhân</th>
                <th>Ngày giờ yêu cầu</th>
                <th>Bác sĩ yêu cầu</th>
                <th>Trạng thái</th>
                <th>Ảnh chính</th>
                <th>Ảnh phụ</th>
                <th>Ghi chú xét nghiệm</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($labTests as $i => $test)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $test->test_name }}</td>
                    <td>{{ $test->department->name ?? '---' }}</td>
                    <td>#{{ $test->medical_record_id }}</td>
                    <td>{{ $test->record->patient->name ?? '---' }}</td>
                    <td>{{ optional($test->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $test->doctor->name ?? $test->doctor->email ?? '---' }}</td>
                    <td>
                        @if($test->status == 'completed')
                            <span class="badge bg-success">Hoàn thành</span>
                        @else
                            <span class="badge bg-warning text-dark">Yêu cầu cập nhật ảnh</span>
                        @endif
                    </td>
                    <td>
                        @if($test->image)
                            <img src="{{ asset('storage/'.$test->image) }}" width="100" class="rounded">
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if(!empty($test->images) && is_array($test->images) && count($test->images))
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($test->images as $img)
                                    <img src="{{ asset('storage/'.$img) }}" width="70" height="70" style="object-fit:cover" class="rounded border">
                                @endforeach
                            </div>
                        @else
                            ---
                        @endif
                    </td>
                    <td>{{ $test->note ?? '---' }}</td>
                    <td>
                        <a href="{{ route('admin.lab_tests.upload', $test->id) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-upload"></i>
                            @if($test->status == 'requested') Upload kết quả @else Cập nhật ảnh @endif
                        </a>
                        <form action="{{ route('admin.lab_tests.destroy', $test->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa xét nghiệm này?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $labTests->links() }}
    </div>
</div>
@endsection
