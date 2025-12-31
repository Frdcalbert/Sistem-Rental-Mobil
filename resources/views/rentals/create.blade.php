@extends('layouts.app')

@section('title', 'Buat Rental Baru')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-key"></i> Buat Rental Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rentals.store') }}" method="POST" id="rentalForm">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Pelanggan *</label>
                            <select class="form-control @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                                            {{ old('car_id') == $car->id ? 'selected' : '' }}>
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
                                   value="{{ old('start_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Tanggal Selesai *</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', date('Y-m-d', strtotime('+3 days'))) }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Preview Biaya -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-calculator"></i> Rincian Biaya</h6>
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
                                    <p class="mb-1"><strong>Total Biaya:</strong></p>
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
                            <i class="fas fa-save"></i> Simpan Rental
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
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Validasi tanggal mulai tidak boleh sebelum hari ini
        if (startDate < today) {
            e.preventDefault();
            alert('Tanggal mulai tidak boleh sebelum hari ini!');
            $('#start_date').focus();
            return false;
        }
        
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