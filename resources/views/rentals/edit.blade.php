@extends('layouts.app')

@section('title', 'Edit Data Rental')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Data Rental</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rentals.update', $rental) }}" method="POST" id="rentalForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Pelanggan *</label>
                            <select class="form-control @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id', $rental->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->driver_license }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="car_id" class="form-label">Mobil *</label>
                            <select class="form-control @error('car_id') is-invalid @enderror" 
                                    id="car_id" name="car_id" required>
                                <option value="">Pilih Mobil</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" 
                                            data-daily-rate="{{ $car->daily_rate }}"
                                            {{ old('car_id', $rental->car_id) == $car->id ? 'selected' : '' }}>
                                        {{ $car->brand }} {{ $car->model }} - 
                                        Rp {{ number_format($car->daily_rate, 0, ',', '.') }}/hari
                                        ({{ $car->license_plate }})
                                    </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', \Carbon\Carbon::parse($rental->start_date)->format('Y-m-d')) }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Tanggal Selesai *</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', \Carbon\Carbon::parse($rental->end_date)->format('Y-m-d')) }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $rental->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ old('status', $rental->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ old('status', $rental->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Informasi Biaya</label>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-1">Total Hari: <strong>{{ $rental->total_days }} hari</strong></p>
                                <p class="mb-1">Total Biaya: <strong>Rp {{ number_format($rental->total_cost, 0, ',', '.') }}</strong></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Biaya -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-calculator"></i> Rincian Biaya Baru</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Harga per Hari:</strong></p>
                                    <h5 id="dailyRatePreview">Rp 0</h5>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Durasi:</strong></p>
                                    <h5 id="durationPreview">0 hari</h5>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Total Biaya Baru:</strong></p>
                                    <h4 id="totalCostPreview" class="text-success">Rp 0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('rentals.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Fungsi untuk menghitung durasi dan biaya
    function calculateRental() {
        const startDate = new Date($('#start_date').val());
        const endDate = new Date($('#end_date').val());
        const dailyRate = parseFloat($('#car_id option:selected').data('daily-rate')) || 0;
        
        if (startDate && endDate && endDate > startDate) {
            // Hitung selisih hari
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            // Update preview
            $('#durationPreview').text(daysDiff + ' hari');
            $('#dailyRatePreview').text('Rp ' + dailyRate.toLocaleString('id-ID'));
            
            // Hitung total biaya
            const totalCost = daysDiff * dailyRate;
            $('#totalCostPreview').text('Rp ' + totalCost.toLocaleString('id-ID'));
        } else {
            $('#durationPreview').text('0 hari');
            $('#dailyRatePreview').text('Rp 0');
            $('#totalCostPreview').text('Rp 0');
        }
    }
    
    // Panggil fungsi saat halaman dimuat
    calculateRental();
    
    // Event listeners
    $('#start_date, #end_date, #car_id').on('change', calculateRental);
    
    // Validasi form
    $('#rentalForm').on('submit', function(e) {
        const startDate = new Date($('#start_date').val());
        const endDate = new Date($('#end_date').val());
        
        // Validasi tanggal selesai harus setelah tanggal mulai
        if (endDate <= startDate) {
            e.preventDefault();
            alert('Tanggal selesai harus setelah tanggal mulai!');
            $('#end_date').focus();
            return false;
        }
        
        return true;
    });
});
</script>
@endsection