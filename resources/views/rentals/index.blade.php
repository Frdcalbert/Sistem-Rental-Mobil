@extends('layouts.app')

@section('title', 'Data Rental')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-key"></i> Data Rental</h1>
    <a href="{{ route('rentals.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Rental Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Transaksi Rental <span class="badge bg-info">{{ $rentals->total() }} data</span></h5>
    </div>
    <div class="card-body">
        @if($rentals->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">ID</th>
                            <th>Pelanggan</th>
                            <th>Mobil</th>
                            <th>Periode</th>
                            <th>Durasi</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $rental)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">#{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <strong>{{ $rental->customer->name }}</strong>
                                <br><small class="text-muted">{{ $rental->customer->phone }}</small>
                            </td>
                            <td>
                                {{ $rental->car->brand }} {{ $rental->car->model }}
                                <br><small class="text-muted">{{ $rental->car->license_plate }}</small>
                            </td>
                            <td>
                                <small>
                                    {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}<br>
                                    <strong>s/d</strong><br>
                                    {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>{{ $rental->total_days }} hari</td>
                            <td>
                                <strong class="text-success">Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</strong>
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
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('rentals.show', $rental) }}" class="btn btn-info me-2" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('rentals.edit', $rental) }}" class="btn btn-warning me-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('rentals.destroy', $rental) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Hapus rental #{{ $rental->id }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger me-2" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $rentals->firstItem() }} - {{ $rentals->lastItem() }} dari {{ $rentals->total() }} rental
                </div>
                <div>
                    {{ $rentals->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada data rental.</p>
                <a href="{{ route('rentals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Rental Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Rental</h6>
                        <h2 class="mb-0">{{ $rentals->total() }}</h2>
                    </div>
                    <i class="fas fa-key fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Rental Aktif</h6>
                        <h2 class="mb-0">{{ $rentals->where('status', 'active')->count() }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection