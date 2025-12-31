@extends('layouts.app')

@section('title', 'Detail Mobil')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-car"></i> Detail Mobil</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="mb-3">
                            <i class="fas fa-car fa-5x text-primary"></i>
                        </div>
                        <h3>{{ $car->brand }} {{ $car->model }}</h3>
                        <p class="text-muted">ID: #{{ $car->id }}</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Kendaraan</h6>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td width="40%"><strong>Merek</strong></td>
                                <td>{{ $car->brand }}</td>
                            </tr>
                            <tr>
                                <td><strong>Model</strong></td>
                                <td>{{ $car->model }}</td>
                            </tr>
                            <tr>
                                <td><strong>Plat Nomor</strong></td>
                                <td>{{ $car->license_plate }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tahun</strong></td>
                                <td>{{ $car->year }}</td>
                            </tr>
                            <tr>
                                <td><strong>Warna</strong></td>
                                <td>{{ $car->color }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Informasi Rental</h6>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td width="40%"><strong>Harga/Hari</strong></td>
                                <td><strong>Rp {{ number_format($car->daily_rate, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    @if($car->status == 'available')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($car->status == 'rented')
                                        <span class="badge bg-danger">Disewa</span>
                                    @else
                                        <span class="badge bg-warning">Maintenance</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Ditambahkan</strong></td>
                                <td>{{ $car->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Diupdate</strong></td>
                                <td>{{ $car->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($car->rentals->count() > 0)
                <div class="mt-4">
                    <h6>Riwayat Rental</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID Rental</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($car->rentals as $rental)
                                <tr>
                                    <td>#{{ $rental->id }}</td>
                                    <td>{{ $rental->customer->name }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $rental->total_days }} hari</td>
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
                </div>
                @else
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> Mobil ini belum pernah disewa.
                </div>
                @endif
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('cars.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('cars.edit', $car) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('cars.destroy', $car) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('Hapus mobil ini? Semua data rental terkait juga akan dihapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection