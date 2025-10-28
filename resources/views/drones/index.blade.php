@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">Daftar Drone</h2>

  <form class="d-flex" method="GET" action="{{ route('drones.index') }}">
    <input class="form-control me-2" name="q" placeholder="Cari model atau deskripsi..." value="{{ request('q') }}">
    <button class="btn btn-primary">Cari</button>
  </form>
</div>

@if($drones->isEmpty())
  <div class="alert alert-info">
    Belum ada drone tersedia. @auth @if(auth()->user()->is_admin ?? false) <a href="{{ route('admin.drones.create') }}" class="btn btn-sm btn-success ms-2">Tambah Drone</a> @endif @endauth
  </div>
@else
  <div class="row g-3">
    @foreach($drones as $drone)
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div style="height:200px; overflow:hidden;">
            <img class="card-img-top w-100" src="{{ $drone->image ? asset('storage/'.$drone->image) : 'https://via.placeholder.com/800x500?text=Drone' }}" alt="{{ $drone->model }}" style="object-fit:cover; height:100%;">
          </div>

          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-1">{{ $drone->model }}</h5>

            <div class="mb-2">
              @php
                $status = strtolower($drone->status);
                $badgeClass = $status === 'available' ? 'bg-success' : ($status === 'booked' ? 'bg-warning text-dark' : 'bg-secondary');
              @endphp
              <span class="badge {{ $badgeClass }} mb-1">{{ ucfirst($status) }}</span>
              <small class="text-muted ms-2">Serial: {{ $drone->serial_no ?? '-' }}</small>
            </div>

            @if($drone->specs && is_array($drone->specs))
              <ul class="list-inline small text-muted mb-2">
                @foreach($drone->specs as $k => $v)
                  <li class="list-inline-item">{{ ucfirst($k) }}: {{ $v }}</li>
                @endforeach
              </ul>
            @endif

            <p class="card-text mb-3">{{ \Illuminate\Support\Str::limit($drone->description, 120) }}</p>

            <div class="mt-auto d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">Rp{{ number_format($drone->hourly_rate,0,',','.') }}/jam</div>
                <small class="text-muted">Deposit Rp{{ number_format($drone->deposit,0,',','.') }}</small>
              </div>

              <div class="d-flex gap-2">
                {{-- Detail visible to all --}}
                <a href="{{ route('drones.show', $drone) }}" class="btn btn-outline-primary btn-sm">Detail</a>

                {{-- Booking hanya untuk user login dan drone available --}}
                @auth
                  @if($drone->status === 'available')
                    <a href="{{ route('bookings.create', $drone) ?? '#' }}" class="btn btn-success btn-sm">Booking</a>
                  @endif
                @endauth

                {{-- Admin controls (conditional based on is_admin flag) --}}
                @if(auth()->check() && (auth()->user()->is_admin ?? false))
                  <a href="{{ route('admin.drones.edit', $drone) }}" class="btn btn-sm btn-outline-warning">Edit</a>

                  <form action="{{ route('admin.drones.destroy', $drone) }}" method="POST" onsubmit="return confirm('Hapus drone ini?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-4 d-flex justify-content-center">
    {{ $drones->withQueryString()->links() }}
  </div>
@endif
@endsection
