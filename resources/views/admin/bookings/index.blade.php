@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Daftar Booking</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.bookings.print', request()->query()) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">
                    Print
                </a>
            </div>
        </div>

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
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $b)
                            <tr>
                                <td>{{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}</td>
                                <td style="min-width:180px;">
                                    {{ $b->user->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $b->user->email ?? '' }}</small>
                                    @if (optional($b->user)->id)
                                        <div class="small text-muted">ID: {{ $b->user->id }}</div>
                                    @endif
                                </td>
                                <td style="min-width:160px;">
                                    {{ $b->drone->model ?? '—' }}<br>
                                    <small class="text-muted">{{ $b->drone->serial_no ?? '' }}</small>
                                </td>
                                <td>{{ $b->duration_hours ?? '-' }} jam</td>
                                <td>Rp{{ number_format($b->price ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($b->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'confirmed' => 'bg-success',
                                            'completed' => 'bg-primary',
                                            'cancelled' => 'bg-danger',
                                            'returned' => 'bg-secondary',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($b->status) }}</span>
                                    @if (!empty($b->fine_amount) && $b->fine_amount > 0)
                                        <div class="small text-danger mt-1">Denda:
                                            Rp{{ number_format($b->fine_amount, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td style="min-width:160px;">
                                    <div>{{ optional($b->start_at)->format('Y-m-d H:i') ?? '-' }}</div>
                                    <div class="text-muted small">{{ optional($b->end_at)->format('Y-m-d H:i') ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-muted small">{{ optional($b->created_at)->format('Y-m-d H:i') }}</td>

                                <td style="white-space:nowrap;">
                                    {{-- View detail (buat route admin.bookings.show jika belum ada) --}}
                                    <a href="{{ route('admin.bookings.show', $b) }}"
                                        class="btn btn-sm btn-outline-primary mb-1">View</a>

                                    {{-- Hanya tampilkan tombol return untuk admin dan bila status belum 'returned' --}}
                                    @if (auth()->check() && (auth()->user()->is_admin ?? false) && $b->status !== 'returned')
                                        <form action="{{ route('admin.bookings.return', $b) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            {{-- gunakan konfirmasi JS kecil --}}
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                onclick="return confirm('Proses pengembalian booking #{{ $b->id }}? Ini akan menandai unit tersedia kembali dan menghitung denda jika ada.')">Proses
                                                Pengembalian</button>
                                        </form>
                                    @endif

                                    {{-- Tombol cetak nota/receipt --}}
                                    <a href="{{ route('admin.bookings.print', array_merge(request()->query(), ['booking_id' => $b->id])) }}"
                                        target="_blank" class="btn btn-sm btn-outline-secondary mt-1">Cetak</a>
                                </td>
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
