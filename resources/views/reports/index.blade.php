@extends('layouts.app')

@section('title', 'Laporan Rental')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-alt"></i> Laporan Rental</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Filter Sederhana -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistik Sederhana -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pendapatan</h6>
                        <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <small class="opacity-75">Periode terpilih</small>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Transaksi</h6>
                        <h3 class="mb-0">{{ $totalRentals }}</h3>
                        <small class="opacity-75">Rental ditemukan</small>
                    </div>
                    <i class="fas fa-file-invoice-dollar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Data Rental 
            <span class="badge bg-info">{{ $rentals->total() }} data</span>
        </h5>
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
                                {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}<br>
                                <small>s/d {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</small>
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