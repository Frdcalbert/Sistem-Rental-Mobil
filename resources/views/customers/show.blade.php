@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Detail Pelanggan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h3>{{ $customer->name }}</h3>
                        <p class="text-muted">ID: #{{ $customer->id }}</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Kontak</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Email</strong></td>
                                <td>{{ $customer->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telepon</strong></td>
                                <td>{{ $customer->phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>{{ $customer->address }}</td>
                            </tr>
                            <tr>
                                <td><strong>SIM</strong></td>
                                <td>{{ $customer->driver_license }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Statistik Rental</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%"><strong>Total Rental</strong></td>
                                <td>{{ $customer->rentals->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rental Aktif</strong></td>
                                <td>{{ $customer->rentals->where('status', 'active')->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Daftar</strong></td>
                                <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($customer->rentals->count() > 0)
                <div class="mt-4">
                    <h6>Riwayat Rental</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID Rental</th>
                                    <th>Mobil</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->rentals as $rental)
                                <tr>
                                    <td>#{{ $rental->id }}</td>
                                    <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                                    <td>{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}</td>
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
                @endif
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('Hapus pelanggan ini?')">
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