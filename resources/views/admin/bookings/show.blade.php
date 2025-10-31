@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary mb-3">← Kembali</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            Booking #{{ $booking->id }} — {{ ucfirst($booking->status) }}
        </div>
        <div class="card-body">
            <p><strong>User:</strong> {{ optional($booking->user)->name }} ({{ optional($booking->user)->email }})</p>
            <p><strong>Drone:</strong> {{ optional($booking->drone)->model }} / {{ optional($booking->drone)->serial_no }}</p>
            <p><strong>Unit:</strong> {{ $booking->drone_unit_id ? ($booking->droneUnit->code ?? $booking->drone_unit_id) : '-' }}</p>
            <p><strong>Periode:</strong> {{ optional($booking->start_at)->format('Y-m-d H:i') }} — {{ optional($booking->end_at)->format('Y-m-d H:i') }}</p>
            <p><strong>Harga:</strong> Rp{{ number_format($booking->price ?? 0,0,',','.') }}</p>
            @if(!empty($booking->fine_amount))
                <p class="text-danger"><strong>Denda:</strong> Rp{{ number_format($booking->fine_amount,0,',','.') }}</p>
            @endif

            {{-- Tombol proses pengembalian --}}
            @if($booking->status !== 'returned')
                <form action="{{ route('admin.bookings.return', $booking->id) }}" method="POST" onsubmit="return confirm('Proses pengembalian?');">
                    @csrf
                    <button class="btn btn-success">Proses Pengembalian</button>
                </form>
            @else
                <div class="alert alert-secondary">Booking sudah dikembalikan pada {{ optional($booking->returned_at)->format('Y-m-d H:i') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
