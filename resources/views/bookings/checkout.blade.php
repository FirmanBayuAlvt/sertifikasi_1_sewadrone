@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-8">
    <h3>Booking: {{ $drone->model }}</h3>
    <form action="{{ route('bookings.store', $drone) }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label">Start (tanggal & waktu)</label>
        <input type="datetime-local" name="start_at" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">End (tanggal & waktu)</label>
        <input type="datetime-local" name="end_at" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Alamat Pickup / Lokasi</label>
        <input type="text" name="pickup_address" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Catatan untuk operator</label>
        <textarea name="notes" class="form-control" rows="3"></textarea>
      </div>

      <div class="card p-3 mb-3">
        <h5>Ringkasan biaya</h5>
        <p>Tarif per jam: <strong>Rp{{ number_format($drone->hourly_rate,0,',','.') }}</strong></p>
        <p>Deposit: <strong>Rp{{ number_format($drone->deposit,0,',','.') }}</strong></p>
        <div class="text-muted small">Pembayaran akan disimulasikan (stub) pada demo ini.</div>
      </div>

      <button class="btn btn-success">Submit & Bayar (stub)</button>
    </form>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5>{{ $drone->model }}</h5>
        <p>{{ \Illuminate\Support\Str::limit($drone->description, 150) }}</p>
        <p class="mb-0">Harga/jam: <strong>Rp{{ number_format($drone->hourly_rate,0,',','.') }}</strong></p>
        <p>Deposit: <strong>Rp{{ number_format($drone->deposit,0,',','.') }}</strong></p>
      </div>
    </div>
  </div>
</div>
@endsection
