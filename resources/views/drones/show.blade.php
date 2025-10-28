@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-7">
    <img src="{{ $drone->image ? asset('storage/'.$drone->image) : 'https://via.placeholder.com/1000x600?text=Drone' }}" class="img-fluid rounded" alt="{{ $drone->model }}">
  </div>
  <div class="col-md-5">
    <h2>{{ $drone->model }}</h2>
    <p class="text-muted">Serial: {{ $drone->serial_no ?? '-' }}</p>
    <p>{{ $drone->description }}</p>

    <ul class="list-group mb-3">
      @if($drone->specs)
        @foreach($drone->specs as $k=>$v)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ ucfirst($k) }} <span class="text-muted">{{ $v }}</span>
          </li>
        @endforeach
      @endif
      <li class="list-group-item d-flex justify-content-between align-items-center">
        Harga/jam <strong>Rp{{ number_format($drone->hourly_rate,0,',','.') }}</strong>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        Deposit <strong>Rp{{ number_format($drone->deposit,0,',','.') }}</strong>
      </li>
      <li class="list-group-item">
        Status: <span class="badge bg-{{ $drone->status == 'available' ? 'success' : ($drone->status == 'booked' ? 'warning' : 'secondary') }}">{{ ucfirst($drone->status) }}</span>
      </li>
    </ul>

    @auth
      @if($drone->status == 'available')
        <a href="{{ route('bookings.create', $drone) }}" class="btn btn-primary btn-lg">Booking Sekarang</a>
      @else
        <button class="btn btn-secondary btn-lg" disabled>Sudah Dipesan</button>
      @endif
    @else
      <a href="{{ route('login') }}" class="btn btn-outline-primary">Login untuk memesan</a>
    @endauth
  </div>
</div>
@endsection
