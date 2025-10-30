@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h3 class="card-title mb-0">Booking Berhasil</h3>
                    <small class="text-muted">Terima kasih — berikut detail booking Anda.</small>
                </div>

                <div class="text-end">
                    <button class="btn btn-outline-secondary btn-sm me-2" onclick="window.print()">Cetak</button>
                    <a href="{{ route('bookings.my') }}" class="btn btn-primary btn-sm">Lihat My Bookings</a>
                </div>
            </div>

            <table class="table table-borderless">
                <tr>
                    <th style="width:200px">Booking ID</th>
                    <td>#{{ $booking->id }}</td>
                </tr>

                <tr>
                    <th>Drone</th>
                    <td>{{ optional($booking->drone)->model ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Unit</th>
                    <td>
                        @if (isset($booking->drone_unit) && $booking->drone_unit)
                            {{ $booking->drone_unit->code ?? 'Unit-' . $booking->drone_unit->id }} —
                            {{ $booking->drone_unit->name ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Mulai</th>
                    <td>{{ optional($booking->start_at)->format('d M Y H:i') ?? $booking->start_at }}</td>
                </tr>

                <tr>
                    <th>Selesai</th>
                    <td>{{ optional($booking->end_at)->format('d M Y H:i') ?? $booking->end_at }}</td>
                </tr>

                <tr>
                    <th>Durasi (jam)</th>
                    <td>{{ $booking->duration_hours ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Durasi (hari)</th>
                    <td>{{ $booking->duration_days ?? (int) ceil((optional($booking->end_at)->diffInSeconds(optional($booking->start_at) ?: now()) ?? 0) / 86400) }}
                    </td>
                </tr>

                <tr>
                    <th>Total Harga (sebelum denda)</th>
                    <td>Rp{{ number_format($booking->price ?? 0, 0, ',', '.') }}</td>
                </tr>

                <tr>
                    <th>Deposit</th>
                    <td>Rp{{ number_format($booking->deposit_paid ?? 0, 0, ',', '.') }}</td>
                </tr>

                <tr>
                    <th>Denda (jika ada)</th>
                    <td>
                        @if (!empty($booking->late_fee) && $booking->late_fee > 0)
                            <div>Hari terlambat: <strong>{{ $booking->late_days }}</strong></div>
                            <div>Rp{{ number_format($booking->late_fee, 0, ',', '.') }}</div>
                            <div class="small text-muted">(Rp{{ number_format($booking->fine_per_day ?? 0, 0, ',', '.') }} /
                                hari)</div>
                        @else
                            Rp0
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Total Dibayarkan</th>
                    <td>
                        @php
                            $late = floatval($booking->late_fee ?? 0);
                            $price = floatval($booking->price ?? 0);
                            $deposit = floatval($booking->deposit_paid ?? 0);
                            $total = $price + $deposit + $late;
                        @endphp
                        Rp{{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($booking->status ?? '-') }}</td>
                </tr>

                <tr>
                    <th>Pickup / Catatan</th>
                    <td>
                        @if ($booking->pickup_address)
                            {{ $booking->pickup_address }}<br>
                        @endif
                        @if ($booking->notes)
                            <small class="text-muted">{{ $booking->notes }}</small>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="mt-3">
                <a href="{{ route('drones.index') }}" class="btn btn-outline-secondary">Kembali ke katalog</a>
                <a href="{{ route('bookings.my') }}" class="btn btn-outline-primary">Lihat Semua Peminjaman Saya</a>
            </div>
        </div>
    </div>

    <style>
        /* Supaya hasil cetak rapih */
        @media print {

            nav,
            .btn,
            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none;
                border: none;
            }
        }
    </style>
@endsection
