<?php

namespace App\Http\Controllers;

use App\Models\Drone;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $totalDrones = Drone::count();
        $bookingsPending = Booking::where('status', 'pending')->count();
        $bookingsConfirmed = Booking::where('status', 'confirmed')->count();

        return view('admin.dashboard', compact('totalDrones', 'bookingsPending', 'bookingsConfirmed'));
    }

    // Drone CRUD
    public function dronesIndex()
    {
        $drones = Drone::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.drones.index', compact('drones'));
    }

    public function dronesCreate()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.drones.create', compact('categories'));
    }

    public function dronesStore(Request $request)
    {
        // validasi termasuk kategori (optional)
        $data = $request->validate([
            'model' => 'required|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,booked,maintenance,inactive',
            'image' => 'nullable|image|max:2048',
            'specs' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        // handle image upload (jika ada)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('drones', 'public');
            $data['image'] = $path;
        }

        // parse specs — terima JSON string atau "key: value" per baris
        if (!empty($data['specs'])) {
            $json = json_decode($data['specs'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['specs'] = $json;
            } else {
                $arr = [];
                foreach (preg_split("/\r\n|\n|\r/", $data['specs']) as $line) {
                    if (strpos($line, ':') !== false) {
                        [$k, $v] = array_map('trim', explode(':', $line, 2));
                        $arr[$k] = $v;
                    }
                }
                $data['specs'] = $arr;
            }
        }

        // buat drone sekali saja
        $drone = Drone::create($data);

        // sync categories jika ada
        if (!empty($data['categories'])) {
            $drone->categories()->sync($data['categories']);
        }

        return redirect()->route('admin.drones.index')->with('success', 'Drone berhasil ditambahkan.');
    }

    public function dronesEdit(Drone $drone)
    {
        // ambil semua kategori untuk checkbox/select
        $categories = \App\Models\Category::orderBy('name')->get();

        // pluck dengan prefix tabel untuk menghindari ambiguous column error
        $selected = $drone->categories()->pluck('categories.id')->toArray();

        return view('admin.drones.edit', compact('drone', 'categories', 'selected'));
    }

    public function dronesUpdate(Request $request, Drone $drone)
    {
        // tambahkan validasi categories juga
        $data = $request->validate([
            'model' => 'required|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,booked,maintenance,inactive',
            'image' => 'nullable|image|max:2048',
            'specs' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        // handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('drones', 'public');
            $data['image'] = $path;
        }

        // parse specs
        if (!empty($data['specs'])) {
            $json = json_decode($data['specs'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['specs'] = $json;
            } else {
                $arr = [];
                foreach (preg_split("/\r\n|\n|\r/", $data['specs']) as $line) {
                    if (strpos($line, ':') !== false) {
                        [$k, $v] = array_map('trim', explode(':', $line, 2));
                        $arr[$k] = $v;
                    }
                }
                $data['specs'] = $arr;
            }
        }

        $drone->update($data);

        // sync categories jika ada (kosongkan jika tidak ada)
        $drone->categories()->sync($data['categories'] ?? []);

        return redirect()->route('admin.drones.index')->with('success', 'Drone diupdate.');
    }

    public function dronesDestroy(Drone $drone)
    {
        $drone->delete();
        return back()->with('success', 'Drone dihapus.');
    }

    // Booking list for admin (dengan filter pencarian)
    public function bookingsIndex(Request $request)
    {
        $query = Booking::with('drone', 'user')->orderBy('start_at', 'desc');

        if ($q = $request->query('q')) {
            $query->whereHas('drone', function ($q2) use ($q) {
                $q2->where('model', 'like', '%' . $q . '%');
            })->orWhereHas('user', function ($q3) use ($q) {
                $q3->where('name', 'like', '%' . $q . '%')->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function bookingsPrint(Request $request)
    {
        $query = Booking::with(['drone', 'user'])->orderBy('start_at', 'desc');

        if ($q = $request->query('q')) {
            $query->whereHas('drone', function ($q2) use ($q) {
                $q2->where('model', 'like', '%' . $q . '%');
            })->orWhereHas('user', function ($q3) use ($q) {
                $q3->where('name', 'like', '%' . $q . '%')->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($startDate = $request->query('start_date')) {
            $query->whereDate('start_at', '>=', $startDate);
        }
        if ($endDate = $request->query('end_date')) {
            $query->whereDate('end_at', '<=', $endDate);
        }

        $bookings = $query->get();
        $filters = $request->only(['q', 'status', 'user_id', 'start_date', 'end_date']);

        return view('admin.bookings.print', compact('bookings', 'filters'));
    }

    // Units (per drone)
    public function unitsCreate(Drone $drone)
    {
        return view('admin.drones.units.create', compact('drone'));
    }

    public function unitsStore(Request $request, Drone $drone)
    {
        $data = $request->validate([
            'code' => 'required|string|max:100|unique:drone_units,code',
            'name' => 'nullable|string|max:255',
            'status' => 'required|in:available,booked,maintenance,retired',
        ]);

        $drone->units()->create($data);

        return redirect()->route('admin.drones.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function unitsDestroy(\App\Models\DroneUnit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit dihapus.');
    }

    // Proses pengembalian booking (hanya admin — pastikan route ada dalam group admin)
    // public function returnBooking(Request $request, \App\Models\Booking $booking)
    // {
    //     if ($booking->status === 'returned') {
    //         return back()->with('info', 'Booking sudah diproses pengembaliannya.');
    //     }

    //     DB::transaction(function () use ($booking) {
    //         $now = Carbon::now();

    //         $start = $booking->start_at ? Carbon::parse($booking->start_at) : null;
    //         $end = $booking->end_at ? Carbon::parse($booking->end_at) : null;

    //         $days = 0;
    //         if ($start && $end) {
    //             $days = max(1, $start->diffInDays($end));
    //         }

    //         $maxDays = 5;
    //         $extraDays = max(0, $days - $maxDays);

    //         $dailyRate = optional($booking->drone)->daily_rate ?? 0;
    //         $finePerDayRate = 0.20; // ubah bila perlu
    //         $fineAmount = $extraDays > 0 ? ($extraDays * $dailyRate * $finePerDayRate) : 0;

    //         $booking->returned_at = $now;
    //         $booking->fine_amount = $fineAmount;
    //         $booking->processed_by = Auth::id();
    //         $booking->status = 'returned';
    //         $booking->save();

    //         if ($booking->drone_unit_id) {
    //             $unit = \App\Models\DroneUnit::find($booking->drone_unit_id);
    //             if ($unit) {
    //                 $unit->status = 'available';
    //                 $unit->save();
    //             }
    //         }

    //         if ($fineAmount > 0) {
    //             Payment::create([
    //                 'booking_id' => $booking->id,
    //                 'amount' => $fineAmount,
    //                 'method' => 'fine',
    //                 'status' => 'unpaid',
    //             ]);
    //         }
    //     });

    //     return back()->with('success', 'Pengembalian diproses. Denda: Rp' . number_format($booking->fine_amount ?? 0, 0, ',', '.'));
    // }


    public function returnBooking(Request $request, \App\Models\Booking $booking)
    {
        // pastikan user terautentikasi dan admin
        $user = Auth::user();
        if (! $user || ! ($user->is_admin ?? false)) {
            abort(403, 'Akses ditolak: hanya admin yang dapat melakukan pengembalian.');
        }

        // ... lanjutkan proses pengembalian seperti yang sudah Anda punya ...
        DB::transaction(function () use ($booking, $user) {
            $now = Carbon::now();
            $start = $booking->start_at ? Carbon::parse($booking->start_at) : null;
            $end = $booking->end_at ? Carbon::parse($booking->end_at) : null;

            $days = 0;
            if ($start && $end) {
                $days = max(1, $start->diffInDays($end));
            }

            $maxDays = 5;
            $extraDays = max(0, $days - $maxDays);
            $dailyRate = optional($booking->drone)->daily_rate ?? 0;
            $finePerDayRate = 0.20;
            $fineAmount = $extraDays > 0 ? ($extraDays * $dailyRate * $finePerDayRate) : 0;

            $booking->returned_at = $now;
            $booking->fine_amount = $fineAmount;
            $booking->processed_by = $user->id;
            $booking->status = 'returned';
            $booking->save();

            if ($booking->drone_unit_id) {
                $unit = \App\Models\DroneUnit::find($booking->drone_unit_id);
                if ($unit) $unit->update(['status' => 'available']);
            }

            if ($fineAmount > 0) {
                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $fineAmount,
                    'method' => 'fine',
                    'status' => 'unpaid',
                ]);
            }
        });

        return back()->with('success', 'Pengembalian diproses. Denda: Rp' . number_format($booking->fine_amount ?? 0, 0, ',', '.'));
    }
}
