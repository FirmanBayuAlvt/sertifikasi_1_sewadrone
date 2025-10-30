@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Daftar Booking</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.bookings.print', request()->query()) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer"></i> Print
                </a>
            </div>
        </div>

        {{-- filter & search --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-auto">
                <input name="q" value="{{ request('q') }}" class="form-control form-control-sm"
                    placeholder="Cari nama user / drone">
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary">Reset</a>
            </div>
        </form>

        @if ($bookings->isEmpty())
            <div class="alert alert-info">Tidak ada booking dengan kriteria ini.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Drone</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Mulai / Selesai</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $b)
                            <tr>
                                <td>{{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}</td>
                                <td>
                                    {{ $b->user->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $b->user->email ?? '' }}</small>
                                </td>
                                <td>{{ $b->drone->model ?? '—' }}<br><small
                                        class="text-muted">{{ $b->drone->serial_no ?? '' }}</small></td>
                                <td>{{ $b->duration_hours ?? '-' }} jam</td>
                                <td>Rp{{ number_format($b->price ?? 0, 0, ',', '.') }}</td>
                                <td><span
                                        class="badge {{ $b->status == 'pending' ? 'bg-warning text-dark' : ($b->status == 'confirmed' ? 'bg-success' : 'bg-secondary') }}">{{ ucfirst($b->status) }}</span>
                                </td>
                                <td>
                                    <div>{{ optional($b->start_at)->format('Y-m-d H:i') }}</div>
                                    <div class="text-muted small">{{ optional($b->end_at)->format('Y-m-d H:i') }}</div>
                                </td>
                                <td class="text-muted small">{{ optional($b->created_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $bookings->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
