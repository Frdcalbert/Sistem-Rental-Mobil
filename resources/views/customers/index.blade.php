@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-users me-2"></i>Data Pelanggan
    </h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pelanggan
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('customers.index') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" 
                       placeholder="Cari pelanggan (nama, email, telepon, SIM)" 
                       value="{{ $search }}">
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="id_desc" {{ $sort == 'id_desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="id_asc" {{ $sort == 'id_asc' ? 'selected' : '' }}>Terlama</option>
                    <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Pelanggan</h5>
        <span class="badge bg-info">{{ $customers->total() }} data</span>
    </div>
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>SIM</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">#{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td><code>{{ $customer->driver_license }}</code></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-info me-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Hapus pelanggan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger me-2">
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $customers->firstItem() }} - {{ $customers->lastItem() }} dari {{ $customers->total() }} data
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
            
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada data pelanggan.</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Pelanggan Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Stats -->
<div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pelanggan</h6>
                        <h2 class="mb-0">{{ $customers->total()}}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
@endsection