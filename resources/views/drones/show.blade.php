@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-7">
            <img src="{{ $drone->image ? asset('storage/' . $drone->image) : 'https://via.placeholder.com/1000x600?text=Drone' }}"
                class="img-fluid rounded" alt="{{ $drone->model }}">
        </div>

        <div class="col-md-5">
            <h2>{{ $drone->model }}</h2>
            <p class="text-muted">Serial: {{ $drone->serial_no ?? '-' }}</p>
            <p>{{ $drone->description }}</p>

            {{-- booking form --}}
            <form method="POST" action="{{ route('bookings.store', $drone) }}">
                @csrf

                {{-- safe access: gunakan koleksi kosong bila variabel tidak tersedia --}}
                @php
                    $availableUnits = isset($availableUnits)
                        ? $availableUnits
                        : (method_exists($drone, 'units')
                            ? $drone->units
                            : collect());
                @endphp

                @if ($availableUnits && $availableUnits->count())
                    <div class="mb-3">
                        <label for="drone_unit_id" class="form-label">Pilih Unit (opsional)</label>
                        <select name="drone_unit_id" id="drone_unit_id" class="form-select">
                            <option value="">{{ __('Biarkan kosong untuk alokasi otomatis') }}</option>
                            @foreach ($availableUnits as $u)
                                <option value="{{ $u->id }}" {{ old('drone_unit_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->code ?? $u->id }} — {{ $u->name ?? '-' }} ({{ $u->status }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="row g-2 mb-3">
                    <div class="col">
                        <label for="start_at" class="form-label">Mulai</label>
                        <input type="datetime-local" id="start_at" name="start_at" value="{{ old('start_at') }}"
                            class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="end_at" class="form-label">Selesai</label>
                        <input type="datetime-local" id="end_at" name="end_at" value="{{ old('end_at') }}"
                            class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pickup_address" class="form-label">Alamat Pengambilan (opsional)</label>
                    <input type="text" id="pickup_address" name="pickup_address" value="{{ old('pickup_address') }}"
                        class="form-control" maxlength="500">
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan (opsional)</label>
                    <textarea id="notes" name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                </div>

                {{-- tombol / kontrol --}}
                <div class="mb-3">
                    @auth
                        @if ($drone->status === 'available')
                            <button type="submit" class="btn btn-primary btn-lg">Pesan Sekarang</button>
                        @else
                            <button type="button" class="btn btn-secondary btn-lg" disabled>Sudah Dipesan</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Login untuk memesan</a>
                    @endauth
                </div>
            </form>

            {{-- spesifikasi & harga --}}
            <ul class="list-group mb-3">
                @if ($drone->specs)
                    @foreach ($drone->specs as $k => $v)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($k) }} <span class="text-muted">{{ $v }}</span>
                        </li>
                    @endforeach
                @endif

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Harga/jam <strong>Rp{{ number_format($drone->hourly_rate, 0, ',', '.') }}</strong>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Deposit <strong>Rp{{ number_format($drone->deposit, 0, ',', '.') }}</strong>
                </li>

                <li class="list-group-item">
                    Status:
                    <span
                        class="badge
                    {{ $drone->status == 'available' ? 'bg-success' : ($drone->status == 'booked' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                        {{ ucfirst($drone->status) }}
                    </span>
                </li>
            </ul>

            {{-- jika kamu tetap ingin menampilkan semua unit (bukan hanya available) --}}
            {{-- @php
                $allUnits = method_exists($drone, 'units') ? $drone->units : collect();
            @endphp --}}

            {{-- @if ($allUnits->count()) --}}
                <h6>Semua Unit</h6>
                <ul class="list-group mb-3">
                    {{-- @foreach ($allUnits as $unit) --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{-- <strong>{{ $unit->code }}</strong> — {{ $unit->name ?? '-' }} --}}
                                {{-- <div class="small text-muted">ID: {{ $unit->id }}</div> --}}
                            </div>
                            <span
                                {{-- class="badge {{ $unit->status == 'available' ? 'bg-success' : ($unit->status == 'booked' ? 'bg-warning text-dark' : 'bg-secondary') }}"> --}}
                                {{-- {{ ucfirst($unit->status) }} --}}
                            </span>
                        </li>
                    {{-- @endforeach --}}
                </ul>
            {{-- @endif --}}

        </div>
    </div>
@endsection
