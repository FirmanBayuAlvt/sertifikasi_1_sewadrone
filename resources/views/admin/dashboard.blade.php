@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h3>Dashboard Admin</h3>
                <p class="text-muted">Selamat datang, {{ auth()->user()->name }}.</p>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Total Drone</h5>
                    <div class="display-6">{{ $totalDrones ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Booking - Pending</h5>
                    <div class="display-6">{{ $bookingsPending ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Booking - Confirmed</h5>
                    <div class="display-6">{{ $bookingsConfirmed ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.drones.index') }}" class="btn btn-primary">Kelola Drone</a>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Lihat Booking</a>
        </div>
    </div>
@endsection
