@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Tambah Drone Baru</h2>
    <form action="{{ route('admin.drones.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" required>
        </div>

        <div class="mb-3">
            <label for="serial_no" class="form-label">Serial Number</label>
            <input type="text" class="form-control" id="serial_no" name="serial_no" required>
        </div>

        <div class="mb-3">
            <label for="specs" class="form-label">Spesifikasi</label>
            <textarea class="form-control" id="specs" name="specs" rows="2" required></textarea>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="hourly_rate" class="form-label">Harga per Jam</label>
            <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" required>
        </div>

        <div class="mb-3">
            <label for="daily_rate" class="form-label">Harga per Hari</label>
            <input type="number" class="form-control" id="daily_rate" name="daily_rate" required>
        </div>

        <div class="mb-3">
            <label for="deposit" class="form-label">Deposit</label>
            <input type="number" class="form-control" id="deposit" name="deposit" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="available">Tersedia</option>
                <option value="rented">Disewa</option>
                <option value="maintenance">Perawatan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
