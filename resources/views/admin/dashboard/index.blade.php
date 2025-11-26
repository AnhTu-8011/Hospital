@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Tiêu đề trang -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">BẢNG ĐIỀU KHIỂN</p>
            <h1 class="h3 mb-0 fw-bold text-dark">📊 Tổng quan hệ thống</h1>
        </div>
    </div>

    <!-- Thẻ thống kê -->
    <div class="row g-4">
        @php
            $cards = [
                ['title' => 'Tổng số bác sĩ', 'value' => $stats['total_doctors'], 'icon' => 'fa-user-md', 'color' => 'primary', 'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'],
                ['title' => 'Lịch hẹn hôm nay', 'value' => $stats['today_appointments'], 'icon' => 'fa-calendar-check', 'color' => 'success', 'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'],
                ['title' => 'Lịch hẹn đã khám', 'value' => $stats['completed_appointments'], 'icon' => 'fa-check-circle', 'color' => 'warning', 'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'],
                ['title' => 'Tổng số bệnh nhân', 'value' => $stats['total_patients'], 'icon' => 'fa-users', 'color' => 'danger', 'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'],
                ['title' => 'Tổng số dịch vụ', 'value' => $stats['total_services'], 'icon' => 'fa-stethoscope', 'color' => 'info', 'gradient' => 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)'],
                ['title' => 'Tổng số khoa', 'value' => $stats['total_departments'], 'icon' => 'fa-hospital', 'color' => 'secondary', 'gradient' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)']
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-lg rounded-4 h-100 overflow-hidden position-relative" 
                 style="background: {{ $card['gradient'] }}; transition: all 0.3s ease;"
                 onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.2)';" 
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.15)';">
                <div class="card-body p-4 text-white position-relative" style="z-index: 2;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-white-50 text-uppercase fw-semibold small mb-2" style="opacity: 0.9;">{{ $card['title'] }}</p>
                            <h2 class="fw-bold text-white mb-0" style="font-size: 2.5rem;">{{ $card['value'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0" style="opacity: 0.1; z-index: 1;">
                    <i class="fas {{ $card['icon'] }}" style="font-size: 8rem;"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">THỐNG KÊ DOANH THU</p>
                <h4 class="fw-bold text-dark mb-0">💰 Phân tích doanh thu</h4>
            </div>

            <!-- Nút 3 chấm -->
            <div class="dropdown">
                <button class="btn btn-light border-0 rounded-pill shadow-sm px-4" type="button" id="chartMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v me-2"></i> Tùy chọn
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3" aria-labelledby="chartMenuButton">
                    <li><a class="dropdown-item chart-option rounded-2" data-type="daily" href="#"><i class="fas fa-calendar-day me-2"></i>Theo ngày</a></li>
                    <li><a class="dropdown-item chart-option rounded-2" data-type="monthly" href="#"><i class="fas fa-calendar-alt me-2"></i>Theo tháng</a></li>
                    <li><a class="dropdown-item chart-option rounded-2" data-type="yearly" href="#"><i class="fas fa-calendar me-2"></i>Theo năm</a></li>
                </ul>
            </div>
        </div>

        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div class="d-flex align-items-center">
                <i class="fas fa-coins fa-2x me-3"></i>
                <div>
                    <strong class="d-block mb-1">Tổng tiền đã thanh toán</strong>
                    <span class="fs-4 fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4 p-4">
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
    cursor: pointer;
}

.dropdown-menu {
    min-width: 180px;
    padding: 8px;
}

.dropdown-item {
    padding: 10px 16px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateX(5px);
}

.chart-container {
    background: #fff;
    border-radius: 12px;
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
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: 20
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
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
