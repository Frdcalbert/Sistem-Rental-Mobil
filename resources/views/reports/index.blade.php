@extends('layouts.app')

@section('title', 'Laporan Rental')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="page-title">
            <i class="fas fa-chart-bar me-2"></i>Laporan Rental
        </h1>
        <p class="text-muted">Analisis data dan statistik rental</p>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="stats-card">
            <div class="stats-icon success">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3 class="stats-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="stats-label">Total Pendapatan</p>
            <small class="text-muted">Periode: {{ $startDate }} s/d {{ $endDate }}</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h3 class="stats-number">{{ $totalRentals }}</h3>
            <p class="stats-label">Total Transaksi</p>
            <small class="text-muted">Rental ditemukan</small>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Data Rental</h5>
        <span class="badge bg-info">{{ $rentals->total() }} data</span>
    </div>
    <div class="card-body">
        @if($rentals->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Mobil</th>
                            <th>Periode</th>
                            <th>Biaya</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $rental)
                        <tr>
                            <td><span class="badge bg-secondary">#{{ $rental->id }}</span></td>
                            <td>{{ $rental->created_at->format('d/m/Y') }}</td>
                            <td>{{ $rental->customer->name }}</td>
                            <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                            <td>
                                <small>
                                    {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}<br>
                                    <strong>s/d</strong><br>
                                    {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <strong class="text-success">
                                    Rp {{ number_format($rental->total_cost, 0, ',', '.') }}
                                </strong>
                            </td>
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $rentals->firstItem() }} - {{ $rentals->lastItem() }} dari {{ $rentals->total() }} data
                </div>
                <div>
                    {{ $rentals->links() }}
                </div>
            </div>
            
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada data rental pada periode yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection