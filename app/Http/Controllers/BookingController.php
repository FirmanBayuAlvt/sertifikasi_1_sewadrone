<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Drone;
use App\Models\Payment;
use App\Models\DroneUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct()
    {
        // Aktifkan auth supaya hanya user ter-login yang bisa booking
        $this->middleware('auth');
    }

    // Tampilkan form checkout; kirim juga daftar unit available (opsional)
    public function create(Drone $drone)
    {
        // jika relasi units sudah ada pada model Drone, ambil unit tersedia
        $availableUnits = [];
        try {
            $availableUnits = $drone->units()->where('status', 'available')->get();
        } catch (\Throwable $e) {
            // jika relasi/drone_units belum dibuat, tetap lanjut tanpa unit
            $availableUnits = collect();
        }

        return view('bookings.checkout', compact('drone', 'availableUnits'));
    }

    // Simpan booking — mendukung pemilihan unit spesifik (drone_unit_id) atau alokasi otomatis
    public function store(Request $request, Drone $drone)
    {
        $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'pickup_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'drone_unit_id' => 'nullable|integer|exists:drone_units,id',
        ]);

        $start = new Carbon($request->start_at);
        $end = new Carbon($request->end_at);

        // duration hours (existing behaviour)
        $hours = max(1, $end->diffInHours($start));

        // duration days: partial day counts as 1 day
        $days = max(1, (int) ceil($end->diffInSeconds($start) / 86400));

        $price = $drone->hourly_rate * $hours;
        $deposit = $drone->deposit;

        try {
            $booking = DB::transaction(function () use ($request, $drone, $start, $end, $hours, $days, $price, $deposit) {
                $user = Auth::user();

                // 1) cek limit overlapping booking = maksimal 2 (kecuali admin)
                $overlappingBookings = Booking::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function ($q) use ($start, $end) {
                        $q->whereBetween('start_at', [$start, $end])
                            ->orWhereBetween('end_at', [$start, $end])
                            ->orWhere(function ($q2) use ($start, $end) {
                                $q2->where('start_at', '<=', $start)->where('end_at', '>=', $end);
                            });
                    })
                    ->lockForUpdate()
                    ->get();

                if (! ($user->is_admin ?? false) && $overlappingBookings->count() >= 2) {
                    throw new \RuntimeException('USER_LIMIT');
                }

                // 2) Pilih / alokasikan unit
                $selectedUnit = null;
                if ($request->filled('drone_unit_id')) {
                    $unit = DroneUnit::where('id', $request->drone_unit_id)
                        ->where('drone_id', $drone->id)
                        ->where('status', 'available')
                        ->first();

                    if (! $unit) {
                        throw new \RuntimeException('NO_UNIT_AVAILABLE');
                    }

                    $selectedUnit = $unit;
                } else {
                    $unit = DroneUnit::where('drone_id', $drone->id)
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    if (! $unit) {
                        throw new \RuntimeException('NO_UNIT_AVAILABLE');
                    }

                    $selectedUnit = $unit;
                }

                // 3) hitung denda apabila durasi hari > MAX_DAYS (default 5)
                $maxDays = config('booking.max_days_without_fine', 5); // buat config/booking.php atau fallback 5
                $finePercent = config('booking.fine_percent_of_daily_rate', 0.20); // fallback 20%

                $lateDays = 0;
                $finePerDay = 0.0;
                $lateFee = 0.0;
                if ($days > $maxDays) {
                    $lateDays = $days - $maxDays;
                    $finePerDay = round(($drone->daily_rate ?? 0) * $finePercent, 2);
                    $lateFee = round($lateDays * $finePerDay, 2);
                }

                // 4) create booking
                $booking = Booking::create([
                    'user_id' => $user->id,
                    'drone_id' => $drone->id,
                    'drone_unit_id' => $selectedUnit->id,
                    'start_at' => $start,
                    'end_at' => $end,
                    'duration_hours' => $hours,
                    'duration_days' => $days,
                    'price' => $price,
                    'deposit_paid' => $deposit,
                    'status' => 'pending',
                    'pickup_address' => $request->pickup_address,
                    'notes' => $request->notes,
                    'late_days' => $lateDays,
                    'fine_per_day' => $finePerDay,
                    'late_fee' => $lateFee,
                ]);

                // 5) tandai unit jadi booked
                $selectedUnit->update(['status' => 'booked']);

                // 6) buat payment (masukkan denda jika ada)
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => round($price + $deposit + $lateFee, 2),
                    'method' => 'manual-stub',
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // 7) konfirmasi booking
                $booking->status = 'confirmed';
                $booking->save();

                return $booking;
            }); // end transaction
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'NO_UNIT_AVAILABLE') {
                return back()->withErrors(['availability' => 'Maaf, tidak ada unit tersedia untuk model ini saat ini.'])->withInput();
            }
            if ($e->getMessage() === 'USER_LIMIT') {
                return back()->withErrors(['limit' => 'Batas peminjaman maksimum tercapai: Anda sudah memiliki 2 peminjaman aktif/overlap pada rentang waktu ini.'])->withInput();
            }
            // error lain
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat booking. Silakan coba lagi.'])->withInput();
        }

        return redirect()->route('bookings.success', $booking->id);
    }


    public function success(Booking $booking)
    {
        return view('bookings.success', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())->orderBy('start_at', 'desc')->paginate(10);
        return view('bookings.my', compact('bookings'));
    }
}
