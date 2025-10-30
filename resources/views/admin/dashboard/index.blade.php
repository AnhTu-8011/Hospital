@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Tiêu đề trang -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h3 mb-3 text-primary fw-bold">📊 Bảng điều khiển</h1>
    </div>

    <!-- Thẻ thống kê -->
    <div class="row">
        @php
            $cards = [
                ['title' => 'Tổng số bác sĩ', 'value' => $stats['total_doctors'], 'icon' => 'fa-user-md', 'color' => 'primary'],
                ['title' => 'Lịch hẹn hôm nay', 'value' => $stats['today_appointments'], 'icon' => 'fa-calendar-check', 'color' => 'success'],
                ['title' => 'Lịch hẹn đã khám', 'value' => $stats['completed_appointments'], 'icon' => 'fa-comments', 'color' => 'warning'],
                ['title' => 'Tổng số bệnh nhân', 'value' => $stats['total_patients'], 'icon' => 'fa-users', 'color' => 'danger'],
                ['title' => 'Tổng số dịch vụ', 'value' => $stats['total_services'], 'icon' => 'fa-stethoscope', 'color' => 'info'],
                ['title' => 'Tổng số khoa', 'value' => $stats['total_departments'], 'icon' => 'fa-hospital', 'color' => 'secondary']
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-start border-4 border-{{ $card['color'] }} shadow-sm rounded-4 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-{{ $card['color'] }} text-uppercase fw-bold small mb-1">{{ $card['title'] }}</p>
                        <h5 class="fw-bold text-dark mb-0">{{ $card['value'] }}</h5>
                    </div>
                    <div class="icon-wrapper bg-{{ $card['color'] }} bg-opacity-10 p-3 rounded-circle">
                        <i class="fas {{ $card['icon'] }} fa-lg text-{{ $card['color'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary mb-0">💰 Thống kê doanh thu</h4>

            <!-- Nút 3 chấm -->
            <div class="dropdown">
                <button class="btn btn-light border rounded-circle" type="button" id="chartMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartMenuButton">
                    <li><a class="dropdown-item chart-option" data-type="daily" href="#">Theo ngày</a></li>
                    <li><a class="dropdown-item chart-option" data-type="monthly" href="#">Theo tháng</a></li>
                    <li><a class="dropdown-item chart-option" data-type="yearly" href="#">Theo năm</a></li>
                </ul>
            </div>
        </div>

        <div class="alert alert-success">
            <strong>Tổng tiền đã thanh toán:</strong>
            {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ
        </div>

        <div class="card shadow-sm p-4">
            <div class="chart-container" style="position: relative; height:500px; width:100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    @auth
        @php
            // Lấy danh sách user là bệnh nhân để admin có thể chọn và chat
            $users = \App\Models\User::whereHas('patient')->get();
        @endphp
        @include('chat.admin', ['users' => $users])
    @endauth

</div>

{{-- CSS --}}
<style>
.stat-card {
    transition: all 0.3s ease;
    background: #fff;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}
.dropdown-menu {
    min-width: 150px;
}
</style>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const datasets = {
    daily: {
        labels: @json($daily->pluck('date')),
        data: @json($daily->pluck('total')),
        label: 'Doanh thu theo ngày (ngày gần nhất)'
    },
    monthly: {
        labels: @json($monthly->pluck('month')),
        data: @json($monthly->pluck('total')),
        label: 'Doanh thu theo tháng (12 tháng gần nhất)'
    },
    yearly: {
        labels: @json($yearly->pluck('year')),
        data: @json($yearly->pluck('total')),
        label: 'Doanh thu theo năm'
    }
};

let currentType = 'daily';
let revenueChart;

function renderChart(type) {
    const ctx = document.getElementById('revenueChart');
    const dataset = datasets[type];

    if (revenueChart) revenueChart.destroy();

    revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataset.labels,
            datasets: [{
                label: dataset.label,
                data: dataset.data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// Khi chọn loại biểu đồ
document.querySelectorAll('.chart-option').forEach(item => {
    item.addEventListener('click', e => {
        e.preventDefault();
        currentType = e.target.dataset.type;
        renderChart(currentType);
    });
});

// Render mặc định là theo ngày
renderChart('daily');
</script>
@endsection
