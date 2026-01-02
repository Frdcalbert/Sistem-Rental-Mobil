@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </h1>
        <p class="text-muted">Sistem Manajemen Rental Mobil</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-lg w-100 py-3">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                            Tambah Pelanggan
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('cars.create') }}" class="btn btn-success btn-lg w-100 py-3">
                            <i class="fas fa-car fa-2x mb-2 d-block"></i>
                            Tambah Mobil
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('rentals.create') }}" class="btn btn-warning btn-lg w-100 py-3">
                            <i class="fas fa-key fa-2x mb-2 d-block"></i>
                            Buat Rental
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('reports.index') }}" class="btn btn-info btn-lg w-100 py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3 class="stats-number">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            <p class="stats-label">Total Pendapatan</p>
            <small class="text-muted">Dari rental selesai</small>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon success">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="stats-number">{{ $stats['total_customers'] }}</h3>
            <p class="stats-label">Total Pelanggan</p>
            <small class="text-muted">Pelanggan terdaftar</small>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon warning">
                <i class="fas fa-car"></i>
            </div>
            <h3 class="stats-number">{{ $stats['available_cars'] }}/{{ $stats['total_cars'] }}</h3>
            <p class="stats-label">Mobil Tersedia</p>
            <small class="text-muted">Dari total inventaris</small>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon danger">
                <i class="fas fa-key"></i>
            </div>
            <h3 class="stats-number">{{ $stats['active_rentals'] }}</h3>
            <p class="stats-label">Rental Aktif</p>
            <small class="text-muted">Sedang berjalan</small>
        </div>
    </div>
</div>

<!-- Recent Rentals -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header2 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Rental Terbaru</h5>
                <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($stats['recent_rentals']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Mobil</th>
                                    <th>Periode</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_rentals'] as $rental)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ $rental->id }}</span></td>
                                    <td>{{ $rental->customer->name }}</td>
                                    <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                                    <td>
                                        <small>
                                            {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}<br>
                                            <strong>s/d</strong><br>
                                            {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</td>
                                    <td>
                                        @if($rental->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($rental->status == 'completed')
                                            <span class="badge bg-info">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data rental.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
`
@endsection