@extends('layouts.app')

@section('title', 'Dashboard - E-Prescription System')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .chart-card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        background: #fff;
    }
    .chart-container {
        position: relative;
        height: 200px;
        min-height: unset;
        padding: 0;
        background: none;
        box-shadow: none;
        border-radius: 0;
    }
    .chart-container canvas {
        height: 200px !important;
        max-height: 220px !important;
        min-height: 180px !important;
        width: 100% !important;
    }
    
    .widget-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        border-left: 4px solid;
    }
    
    .widget-card.primary { border-left-color: #007bff; }
    .widget-card.success { border-left-color: #28a745; }
    .widget-card.warning { border-left-color: #ffc107; }
    .widget-card.danger { border-left-color: #dc3545; }
    .widget-card.info { border-left-color: #17a2b8; }
    
    .quick-action-btn {
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .alert-widget {
        border-radius: 10px;
        border: none;
        margin-bottom: 15px;
    }
    
    .progress-ring {
        width: 80px;
        height: 80px;
    }
    
    .progress-ring circle {
        fill: none;
        stroke-width: 8;
        stroke-linecap: round;
    }
    
    .progress-ring .bg {
        stroke: #e9ecef;
    }
    
    .progress-ring .progress {
        stroke: #007bff;
        stroke-dasharray: 251.2;
        stroke-dashoffset: 251.2;
        transition: stroke-dashoffset 0.5s ease-in-out;
    }
    
    /* Fix for dashboard layout */
    .dashboard-content {
        padding: 20px 0;
    }
    
    .stats-row {
        margin-bottom: 30px;
    }
    
    .charts-section {
        margin-bottom: 30px;
    }
    
    .widgets-section {
        margin-bottom: 30px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .chart-container, .chart-container canvas {
            min-height: 120px !important;
            height: 120px !important;
            max-height: 140px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-content">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
            <i class="fas fa-tachometer-alt"></i> Dashboard
                    </h2>
                    <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }} ({{ Auth::user()->getRoleDisplayName() }})</p>
                </div>
                <div class="text-end">
                    <p class="text-muted mb-0">{{ now()->format('l, d F Y') }}</p>
                    <p class="text-muted mb-0">{{ now()->format('H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row stats-row">
        @if(Auth::user()->isAdmin())
            <!-- Admin Statistics -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white me-3">
                            <i class="fas fa-prescription-bottle-medical"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['total_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Total Resep</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white me-3">
                            <i class="fas fa-clock"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['pending_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Menunggu Review</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="fas fa-check-circle"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['completed_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-danger text-white me-3">
                            <i class="fas fa-exclamation-triangle"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['low_stock_items'] }}</h4>
                            <p class="text-muted mb-0">Stok Menipis</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif(Auth::user()->isDokter())
            <!-- Dokter Statistics -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white me-3">
                            <i class="fas fa-user-md"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['my_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Resep Saya</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white me-3">
                            <i class="fas fa-clock"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['pending_approvals'] }}</h4>
                            <p class="text-muted mb-0">Menunggu Review</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="fas fa-check"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['approved_by_me'] }}</h4>
                            <p class="text-muted mb-0">Disetujui</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-info text-white me-3">
                            <i class="fas fa-pills"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['total_obatalkes'] }}</h4>
                            <p class="text-muted mb-0">Total Obat</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif(Auth::user()->isApoteker())
            <!-- Apoteker Statistics -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="fas fa-check-circle"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['approved_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Disetujui</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white me-3">
                            <i class="fas fa-check-double"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['completed_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-danger text-white me-3">
                            <i class="fas fa-exclamation-triangle"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['low_stock_items'] }}</h4>
                            <p class="text-muted mb-0">Stok Menipis</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white me-3">
                            <i class="fas fa-times-circle"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['out_of_stock_items'] }}</h4>
                            <p class="text-muted mb-0">Habis Stok</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif(Auth::user()->isPasien())
            <!-- Pasien Statistics -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white me-3">
                            <i class="fas fa-prescription-bottle-medical"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['my_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Total Resep</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white me-3">
                            <i class="fas fa-clock"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['pending_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Menunggu Review</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="fas fa-check-circle"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['completed_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-info text-white me-3">
                            <i class="fas fa-edit"></i>
                                </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['draft_prescriptions'] }}</h4>
                            <p class="text-muted mb-0">Draft</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    @if(Auth::user()->canInputResep())
    <div class="row mb-4">
        <div class="col-12">
            <div class="widget-card primary">
                <h5 class="mb-3">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('resep.create') }}" class="btn btn-primary quick-action-btn">
                        <i class="fas fa-plus me-2"></i>Buat Resep Baru
                    </a>
                    <a href="{{ route('resep.index') }}" class="btn btn-outline-primary quick-action-btn">
                        <i class="fas fa-list me-2"></i>Lihat Semua Resep
                                                </a>
                    @if(Auth::user()->canManageMasterData())
                    <a href="{{ route('obatalkes.index') }}" class="btn btn-outline-success quick-action-btn">
                        <i class="fas fa-pills me-2"></i>Kelola Obat
                    </a>
                    <a href="{{ route('signa.index') }}" class="btn btn-outline-info quick-action-btn">
                        <i class="fas fa-sticky-note me-2"></i>Kelola Signa
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Charts and Widgets -->
    <div class="row charts-section">
        <!-- Charts -->
        <div class="col-lg-8">
            @if(Auth::user()->isAdmin())
                <!-- Admin Charts -->
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Status Resep</h5>
                        <div class="chart-container">
                            <canvas id="prescriptionStatusChart"></canvas>
                        </div>
                                    </div>
                                </div>
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Resep Bulanan</h5>
                        <div class="chart-container">
                            <canvas id="monthlyPrescriptionsChart"></canvas>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->isDokter())
                <!-- Dokter Charts -->
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Status Resep Saya</h5>
                        <div class="chart-container">
                            <canvas id="myPrescriptionStatusChart"></canvas>
                        </div>
                                    </div>
                                </div>
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-bar"></i> Review Resep</h5>
                        <div class="chart-container">
                            <canvas id="pendingApprovalsChart"></canvas>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->isApoteker())
                <!-- Apoteker Charts -->
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Status Proses Resep</h5>
                        <div class="chart-container">
                            <canvas id="prescriptionProcessingChart"></canvas>
                        </div>
                                    </div>
                                </div>
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-bar"></i> Level Stok</h5>
                        <div class="chart-container">
                            <canvas id="stockLevelsChart"></canvas>
                        </div>
                    </div>
                    </div>
            @elseif(Auth::user()->isPasien())
                <!-- Pasien Charts -->
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Status Resep Saya</h5>
                        <div class="chart-container">
                            <canvas id="myPrescriptionStatusChart"></canvas>
                        </div>
                            </div>
                        </div>
                <div class="card chart-card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Resep Bulanan</h5>
                        <div class="chart-container">
                            <canvas id="myMonthlyPrescriptionsChart"></canvas>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Widgets -->
        <div class="col-lg-4">
            @if(Auth::user()->isAdmin() || Auth::user()->isApoteker())
                <!-- Stock Alerts -->
                @if(isset($roleData['lowStockAlerts']) && $roleData['lowStockAlerts']->count() > 0)
                <div class="widget-card warning">
                    <h6 class="mb-3">
                        <i class="fas fa-exclamation-triangle"></i> Stok Menipis
                    </h6>
                    @foreach($roleData['lowStockAlerts'] as $item)
                    <div class="alert alert-warning alert-widget py-2">
                        <small><strong>{{ $item->obatalkes_nama }}</strong></small><br>
                        <small>Stok: {{ $item->stok }}</small>
                    </div>
                    @endforeach
                </div>
                @endif
                
                @if(isset($roleData['outOfStockItems']) && $roleData['outOfStockItems']->count() > 0)
                <div class="widget-card danger">
                    <h6 class="mb-3">
                        <i class="fas fa-times-circle"></i> Habis Stok
                    </h6>
                    @foreach($roleData['outOfStockItems'] as $item)
                    <div class="alert alert-danger alert-widget py-2">
                        <small><strong>{{ $item->obatalkes_nama }}</strong></small><br>
                        <small>Stok: {{ $item->stok }}</small>
                    </div>
                    @endforeach
                </div>
                @endif
            @endif
            
            @if(Auth::user()->isAdmin() || Auth::user()->isDokter())
                <!-- Pending Approvals -->
                @if(isset($roleData['pendingApprovals']) && $roleData['pendingApprovals']->count() > 0)
                <div class="widget-card warning">
                    <h6 class="mb-3">
                        <i class="fas fa-clock"></i> Menunggu Review
                    </h6>
                    @foreach($roleData['pendingApprovals'] as $resep)
                    <div class="alert alert-warning alert-widget py-2">
                        <small><strong>{{ $resep->no_resep }}</strong></small><br>
                        <small>{{ $resep->nama_pasien }} - {{ $resep->user->name }}</small><br>
                        <small class="text-muted">{{ $resep->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                    <a href="{{ route('resep.index') }}" class="btn btn-sm btn-warning w-100">
                        Lihat Semua
                    </a>
                </div>
                @endif
            @endif
            
            @if(Auth::user()->isApoteker())
                <!-- Waiting to Receive -->
                @if(isset($roleData['waitingToReceive']) && $roleData['waitingToReceive']->count() > 0)
                <div class="widget-card success">
                    <h6 class="mb-3">
                        <i class="fas fa-hand-holding-medical"></i> Siap Diproses
                    </h6>
                    @foreach($roleData['waitingToReceive'] as $resep)
                    <div class="alert alert-success alert-widget py-2">
                        <small><strong>{{ $resep->no_resep }}</strong></small><br>
                        <small>{{ $resep->nama_pasien }}</small><br>
                        <small class="text-muted">{{ $resep->approved_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                    <a href="{{ route('resep.index') }}" class="btn btn-sm btn-success w-100">
                        Lihat Semua
                    </a>
                </div>
                @endif
                
                <!-- Processing -->
                @if(isset($roleData['processingPrescriptions']) && $roleData['processingPrescriptions']->count() > 0)
                <div class="widget-card info">
                    <h6 class="mb-3">
                        <i class="fas fa-cogs"></i> Sedang Diproses
                    </h6>
                    @foreach($roleData['processingPrescriptions'] as $resep)
                    <div class="alert alert-info alert-widget py-2">
                        <small><strong>{{ $resep->no_resep }}</strong></small><br>
                        <small>{{ $resep->nama_pasien }}</small><br>
                        <small class="text-muted">{{ $resep->received_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                    <a href="{{ route('resep.index') }}" class="btn btn-sm btn-info w-100">
                        Lihat Semua
                    </a>
                </div>
                @endif
            @endif
            
            @if(Auth::user()->isDokter())
                <!-- My Recent Approvals -->
                @if(isset($roleData['myRecentApprovals']) && $roleData['myRecentApprovals']->count() > 0)
                <div class="widget-card success">
                    <h6 class="mb-3">
                        <i class="fas fa-check"></i> Approval Terbaru
                    </h6>
                    @foreach($roleData['myRecentApprovals'] as $resep)
                    <div class="alert alert-success alert-widget py-2">
                        <small><strong>{{ $resep->no_resep }}</strong></small><br>
                        <small>{{ $resep->nama_pasien }}</small><br>
                        <small class="text-muted">{{ $resep->approved_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Recent Prescriptions -->
    <div class="row">
        <div class="col-12">
            <div class="widget-card primary">
                <h5 class="mb-3">
                    <i class="fas fa-history"></i> Resep Terbaru
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No Resep</th>
                                <th>Nama Pasien</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPrescriptions as $resep)
                            <tr>
                                <td><strong>{{ $resep->no_resep }}</strong></td>
                                <td>{{ $resep->nama_pasien }}</td>
                                <td>
                                    @if($resep->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($resep->status == 'pending')
                                        <span class="badge bg-warning">Menunggu Review</span>
                                    @elseif($resep->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($resep->status == 'processing')
                                        <span class="badge bg-info">Sedang Diproses</span>
                                    @elseif($resep->status == 'completed')
                                        <span class="badge bg-primary">Selesai</span>
                                    @elseif($resep->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $resep->user->name }}</td>
                                <td>{{ $resep->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('resep.show', $resep->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Belum ada resep</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart colors
const colors = {
    primary: '#007bff',
    success: '#28a745',
    warning: '#ffc107',
    danger: '#dc3545',
    info: '#17a2b8',
    secondary: '#6c757d'
};

// Initialize charts based on user role
document.addEventListener('DOMContentLoaded', function() {
    var userRole = '{{ Auth::user()->role }}';
    var chartsData = {!! json_encode($chartsData) !!};
    
    if (userRole === 'admin') {
        // Admin charts
        if (chartsData.prescriptionStatus) {
            createPieChart('prescriptionStatusChart', chartsData.prescriptionStatus, 'Status Resep');
        }
        if (chartsData.monthlyPrescriptions) {
            createLineChart('monthlyPrescriptionsChart', chartsData.monthlyPrescriptions, 'Resep Bulanan');
        }
    } else if (userRole === 'dokter') {
        // Dokter charts
        if (chartsData.myPrescriptionStatus) {
            createPieChart('myPrescriptionStatusChart', chartsData.myPrescriptionStatus, 'Status Resep Saya');
        }
        if (chartsData.pendingApprovals) {
            createBarChart('pendingApprovalsChart', chartsData.pendingApprovals, 'Review Resep');
        }
    } else if (userRole === 'apoteker') {
        // Apoteker charts
        if (chartsData.prescriptionProcessing) {
            createPieChart('prescriptionProcessingChart', chartsData.prescriptionProcessing, 'Status Proses Resep');
        }
        if (chartsData.stockLevels) {
            createBarChart('stockLevelsChart', chartsData.stockLevels, 'Level Stok');
        }
    } else if (userRole === 'pasien') {
        // Pasien charts
        if (chartsData.myPrescriptionStatus) {
            createPieChart('myPrescriptionStatusChart', chartsData.myPrescriptionStatus, 'Status Resep Saya');
        }
        if (chartsData.myMonthlyPrescriptions) {
            createLineChart('myMonthlyPrescriptionsChart', chartsData.myMonthlyPrescriptions, 'Resep Bulanan');
        }
    }
});

function createPieChart(canvasId, data, title) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: [
                    colors.secondary,
                    colors.warning,
                    colors.success,
                    colors.danger,
                    colors.info,
                    colors.primary
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function createBarChart(canvasId, data, title) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: title,
                data: data.data,
                backgroundColor: [
                    colors.warning,
                    colors.success,
                    colors.danger
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function createLineChart(canvasId, data, title) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: title,
                data: data.data,
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
</script>
@endpush 