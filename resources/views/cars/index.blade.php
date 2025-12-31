@extends('layouts.app')

@section('title', 'Data Mobil')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-car"></i> Data Mobil</h1>
    <a href="{{ route('cars.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Mobil
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('cars.index') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Cari mobil (merek, model, plat, warna)" 
                           value="{{ $search }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if($search || $status)
                        <a href="{{ route('cars.index') }}" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ $status == 'rented' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ $status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="id_desc" {{ $sort == 'id_desc' ? 'selected' : '' }}>ID: Terbaru ke Terlama</option>
                    <option value="id_asc" {{ $sort == 'id_asc' ? 'selected' : '' }}>ID: Terlama ke Terbaru</option>
                    <option value="brand_asc" {{ $sort == 'brand_asc' ? 'selected' : '' }}>Merek: A ke Z</option>
                    <option value="brand_desc" {{ $sort == 'brand_desc' ? 'selected' : '' }}>Merek: Z ke A</option>
                    <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                    <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    <option value="year_asc" {{ $sort == 'year_asc' ? 'selected' : '' }}>Tahun: Lama ke Baru</option>
                    <option value="year_desc" {{ $sort == 'year_desc' ? 'selected' : '' }}>Tahun: Baru ke Lama</option>
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
            Daftar Mobil 
            <span class="badge bg-info">{{ $cars->total() }} data</span>
            @if($search)
                <span class="badge bg-warning">Hasil pencarian: "{{ $search }}"</span>
            @endif
            @if($status)
                <span class="badge bg-{{ $status == 'available' ? 'success' : ($status == 'rented' ? 'danger' : 'warning') }}">
                    Status: {{ $status == 'available' ? 'Tersedia' : ($status == 'rented' ? 'Disewa' : 'Maintenance') }}
                </span>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($cars->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">ID</th>
                            <th>Merek/Model</th>
                            <th>Plat Nomor</th>
                            <th>Tahun</th>
                            <th>Harga/Hari</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cars as $car)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">#{{ str_pad($car->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <strong>{{ $car->brand }}</strong> {{ $car->model }}
                                <br><small class="text-muted">{{ $car->color }}</small>
                            </td>
                            <td><code>{{ $car->license_plate }}</code></td>
                            <td>{{ $car->year }}</td>
                            <td>Rp {{ number_format($car->daily_rate, 0, ',', '.') }}</td>
                            <td>
                                @if($car->status == 'available')
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($car->status == 'rented')
                                    <span class="badge bg-danger">Disewa</span>
                                @else
                                    <span class="badge bg-warning">Maintenance</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('cars.show', $car) }}" class="btn btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('cars.edit', $car) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('cars.destroy', $car) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Hapus mobil {{ $car->brand }} {{ $car->model }}?')">
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
                    Menampilkan {{ $cars->firstItem() }} - {{ $cars->lastItem() }} dari {{ $cars->total() }} mobil
                </div>
                <div>
                    {{ $cars->links() }}
                </div>
            </div>
            
        @else
            <div class="text-center py-5">
                <i class="fas fa-car fa-3x text-muted mb-3"></i>
                @if($search || $status)
                    <p class="text-muted">Tidak ditemukan mobil dengan kriteria yang dipilih</p>
                    <a href="{{ route('cars.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-times"></i> Tampilkan Semua
                    </a>
                @else
                    <p class="text-muted">Belum ada data mobil.</p>
                    <a href="{{ route('cars.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Mobil Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Mobil</h6>
                        <h2 class="mb-0">{{ $cars->total() }}</h2>
                    </div>
                    <i class="fas fa-car fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Tersedia</h6>
                        <h2 class="mb-0">{{ $cars->where('status', 'available')->count() }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Disewa</h6>
                        <h2 class="mb-0">{{ $cars->where('status', 'rented')->count() }}</h2>
                    </div>
                    <i class="fas fa-key fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Maintenance</h6>
                        <h2 class="mb-0">{{ $cars->where('status', 'maintenance')->count() }}</h2>
                    </div>
                    <i class="fas fa-tools fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection