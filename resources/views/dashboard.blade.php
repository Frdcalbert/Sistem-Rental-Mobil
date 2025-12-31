@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p class="text-muted">Sistem Manajemen Rental Mobil</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pelanggan</h6>
                        <h2 class="mb-0">{{ $stats['total_customers'] }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Mobil</h6>
                        <h2 class="mb-0">{{ $stats['total_cars'] }}</h2>
                    </div>
                    <i class="fas fa-car fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Mobil Tersedia</h6>
                        <h2 class="mb-0">{{ $stats['available_cars'] }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Rental Aktif</h6>
                        <h2 class="mb-0">{{ $stats['active_rentals'] }}</h2>
                    </div>
                    <i class="fas fa-key fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Rentals -->
<!-- Recent Rentals -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> 5 Rental Terbaru</h5>
            </div>
            <div class="card-body">
                @if($stats['recent_rentals']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th>Pelanggan</th>
                                    <th>Mobil</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_rentals'] as $rental)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>{{ $rental->customer->name }}</td>
                                    <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</td>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-2">
                        <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua Rental <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada data rental.</p>
                @endif
            </div>
        </div>
    </div>
</div>


<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-user-plus"></i><br>
                            Tambah Pelanggan
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('cars.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-car"></i><br>
                            Tambah Mobil
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('rentals.create') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-key"></i><br>
                            Buat Rental
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('rentals.index') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-list"></i><br>
                            Lihat Semua Rental
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection