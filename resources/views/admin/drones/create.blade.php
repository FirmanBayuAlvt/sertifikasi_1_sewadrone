@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Tambah Drone Baru</h2>

        {{-- Tampilkan error global --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.drones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="serial_no" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_no" name="serial_no" value="{{ old('serial_no') }}">
                <div class="form-text">Opsional — jika tidak ada, biarkan kosong.</div>
            </div>

            <div class="mb-3">
                <label for="specs" class="form-label">Spesifikasi (format JSON atau tiap baris: key:value)</label>
                <textarea class="form-control" id="specs" name="specs" rows="2">{{ old('specs') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="hourly_rate" class="form-label">Harga per Jam</label>
                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate"
                    value="{{ old('hourly_rate') }}" required min="0">
            </div>

            <div class="mb-3">
                <label for="daily_rate" class="form-label">Harga per Hari</label>
                <input type="number" class="form-control" id="daily_rate" name="daily_rate" value="{{ old('daily_rate') }}"
                    min="0">
            </div>

            <div class="mb-3">
                <label for="deposit" class="form-label">Deposit</label>
                <input type="number" class="form-control" id="deposit" name="deposit" value="{{ old('deposit') }}"
                    min="0">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>Dipesan</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar (opsional)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.drones.index') }}" class="btn btn-secondary">Batal</a>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <div>
                    @foreach ($categories as $cat)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="categories[]"
                                id="cat_{{ $cat->id }}" value="{{ $cat->id }}"
                                {{ isset($selected) && in_array($cat->id, $selected) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

        </form>
    </div>
@endsection
