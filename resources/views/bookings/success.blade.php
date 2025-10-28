@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <h3 class="card-title">Booking Berhasil</h3>
    <p class="text-muted">Terima kasih! Berikut detail booking Anda:</p>

    <table class="table table-borderless">
      <tr><th>Booking ID</th><td>#{{ $booking->id }}</td></tr>
      <tr><th>Drone</th><td>{{ $booking->drone->model }}</td></tr>
      <tr><th>Start</th><td>{{ $booking->start_at }}</td></tr>
      <tr><th>End</th><td>{{ $booking->end_at }}</td></tr>
      <tr><th>Durasi (jam)</th><td>{{ $booking->duration_hours }}</td></tr>
      <tr><th>Total Harga</th><td>Rp{{ number_format($booking->price,0,',','.') }}</td></tr>
      <tr><th>Deposit</th><td>Rp{{ number_format($booking->deposit_paid,0,',','.') }}</td></tr>
      <tr><th>Status</th><td>{{ ucfirst($booking->status) }}</td></tr>
    </table>

    <a href="{{ route('drones.index') }}" class="btn btn-primary">Kembali ke katalog</a>
  </div>
</div>
@endsection
