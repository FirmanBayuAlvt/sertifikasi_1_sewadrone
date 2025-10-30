@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Edit Drone</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.drones.update', $drone) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Model</label>
                <input name="model" value="{{ old('model', $drone->model) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Serial No</label>
                <input name="serial_no" value="{{ old('serial_no', $drone->serial_no) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Spesifikasi (JSON atau tiap baris key:value)</label>
                <textarea name="specs" class="form-control" rows="2">{{ old('specs', is_array($drone->specs) ? json_encode($drone->specs) : $drone->specs) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $drone->description) }}</textarea>
            </div>

            <div class="row g-2">
                <div class="col">
                    <label class="form-label">Harga /jam</label>
                    <input name="hourly_rate" type="number" min="0" class="form-control"
                        value="{{ old('hourly_rate', $drone->hourly_rate) }}">
                </div>
                <div class="col">
                    <label class="form-label">Harga /hari</label>
                    <input name="daily_rate" type="number" min="0" class="form-control"
                        value="{{ old('daily_rate', $drone->daily_rate) }}">
                </div>
                <div class="col">
                    <label class="form-label">Deposit</label>
                    <input name="deposit" type="number" min="0" class="form-control"
                        value="{{ old('deposit', $drone->deposit) }}">
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="available" {{ old('status', $drone->status) == 'available' ? 'selected' : '' }}>Tersedia
                    </option>
                    <option value="booked" {{ old('status', $drone->status) == 'booked' ? 'selected' : '' }}>Dipesan
                    </option>
                    <option value="maintenance" {{ old('status', $drone->status) == 'maintenance' ? 'selected' : '' }}>
                        Perawatan</option>
                    <option value="inactive" {{ old('status', $drone->status) == 'inactive' ? 'selected' : '' }}>Tidak
                        aktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar (opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if ($drone->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $drone->image) }}" style="max-width:200px; height:auto;">
                    </div>
                @endif
            </div>

            <div class="mt-3">
                <button class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.drones.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
