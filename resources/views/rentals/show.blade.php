@extends('layouts.app')

@section('title', 'Detail Rental')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-key"></i> Detail Rental</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="mb-3">
                            <i class="fas fa-file-contract fa-5x text-primary"></i>
                        </div>
                        <h3>Rental #{{ $rental->id }}</h3>
                        <p class="text-muted">Tanggal Transaksi: {{ $rental->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pelanggan</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Nama</strong></td>
                                <td>{{ $rental->customer->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $rental->customer->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telepon</strong></td>
                                <td>{{ $rental->customer->phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>SIM</strong></td>
                                <td>{{ $rental->customer->driver_license }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Informasi Mobil</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Mobil</strong></td>
                                <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                            </tr>
                            <tr>
                                <td><strong>Plat Nomor</strong></td>
                                <td>{{ $rental->car->license_plate }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tahun</strong></td>
                                <td>{{ $rental->car->year }}</td>
                            </tr>
                            <tr>
                                <td><strong>Harga/Hari</strong></td>
                                <td>Rp {{ number_format($rental->car->daily_rate, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h6>Detail Rental</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="30%"><strong>Tanggal Mulai</strong></td>
                                <td>{{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Selesai</strong></td>
                                <td>{{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Durasi</strong></td>
                                <td>{{ $rental->total_days }} hari</td>
                            </tr>
                            <tr>
                                <td><strong>Total Biaya</strong></td>
                                <td><strong>Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
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
                        </table>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('rentals.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('rentals.edit', $rental) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('rentals.destroy', $rental) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('Hapus data rental ini?')">
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