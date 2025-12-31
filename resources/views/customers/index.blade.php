@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users"></i> Data Pelanggan</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Pelanggan
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('customers.index') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Cari pelanggan (nama, email, telepon, SIM)" 
                           value="{{ $search }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if($search)
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="id_desc" {{ $sort == 'id_desc' ? 'selected' : '' }}>ID: Terbaru ke Terlama</option>
                    <option value="id_asc" {{ $sort == 'id_asc' ? 'selected' : '' }}>ID: Terlama ke Terbaru</option>
                    <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Nama: A ke Z</option>
                    <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Nama: Z ke A</option>
                    <option value="email_asc" {{ $sort == 'email_asc' ? 'selected' : '' }}>Email: A ke Z</option>
                    <option value="email_desc" {{ $sort == 'email_desc' ? 'selected' : '' }}>Email: Z ke A</option>
                    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Tanggal: Terbaru</option>
                    <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Tanggal: Terlama</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            Daftar Pelanggan 
            <span class="badge bg-info">{{ $customers->total() }} data</span>
            @if($search)
                <span class="badge bg-warning">Hasil pencarian: "{{ $search }}"</span>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Hapus pelanggan {{ $customer->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus">
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
                    Menampilkan {{ $customers->firstItem() }} - {{ $customers->lastItem() }} dari {{ $customers->total() }} pelanggan
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
            
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                @if($search)
                    <p class="text-muted">Tidak ditemukan pelanggan dengan kata kunci "{{ $search }}"</p>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-times"></i> Tampilkan Semua
                    </a>
                @else
                    <p class="text-muted">Belum ada data pelanggan.</p>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pelanggan Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pelanggan</h6>
                        <h2 class="mb-0">{{ $customers->total() }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection