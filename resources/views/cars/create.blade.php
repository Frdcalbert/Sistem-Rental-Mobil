@extends('layouts.app')

@section('title', 'Tambah Mobil')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-car"></i> Tambah Mobil Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('cars.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="brand" class="form-label">Merek *</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                   id="brand" name="brand" value="{{ old('brand') }}" required>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="model" class="form-label">Model *</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                   id="model" name="model" value="{{ old('model') }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="license_plate" class="form-label">Plat Nomor *</label>
                            <input type="text" class="form-control @error('license_plate') is-invalid @enderror" 
                                   id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required>
                            @error('license_plate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="year" class="form-label">Tahun *</label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                   id="year" name="year" value="{{ old('year', date('Y')) }}" 
                                   min="2000" max="{{ date('Y') }}" required>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="daily_rate" class="form-label">Harga per Hari (Rp) *</label>
                            <input type="number" class="form-control @error('daily_rate') is-invalid @enderror" 
                                   id="daily_rate" name="daily_rate" value="{{ old('daily_rate', 300000) }}" 
                                   min="100000" step="50000" required>
                            @error('daily_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="form-label">Warna *</label>
                            <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                   id="color" name="color" value="{{ old('color') }}" required>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" 
                                       id="status_available" value="available" checked>
                                <label class="form-check-label" for="status_available">
                                    <span class="badge bg-success">Tersedia</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" 
                                       id="status_maintenance" value="maintenance">
                                <label class="form-check-label" for="status_maintenance">
                                    <span class="badge bg-warning">Maintenance</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cars.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Mobil
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
    // Format input harga
    $('#daily_rate').on('input', function() {
        let value = $(this).val();
        if (value < 100000) {
            $(this).val(100000);
        }
    });
    
    // Preview harga
    $('#daily_rate').on('change', function() {
        let value = $(this).val();
        $('#dailyRatePreview').text('Rp ' + parseInt(value).toLocaleString('id-ID'));
    });
});
</script>
@endsection