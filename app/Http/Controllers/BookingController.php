<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Drone;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function create(Drone $drone)
    {
        return view('bookings.checkout', compact('drone'));
    }

    public function store(Request $request, Drone $drone)
    {
        $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'pickup_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $start = new Carbon($request->start_at);
        $end = new Carbon($request->end_at);
        $hours = max(1, $end->diffInHours($start));

        $price = $drone->hourly_rate * $hours;
        $deposit = $drone->deposit;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'drone_id' => $drone->id,
            'start_at' => $start,
            'end_at' => $end,
            'duration_hours' => $hours,
            'price' => $price,
            'deposit_paid' => $deposit,
            'status' => 'pending',
            'pickup_address' => $request->pickup_address,
            'notes' => $request->notes,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $price + $deposit,
            'method' => 'manual-stub',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $booking->status = 'confirmed';
        $booking->save();

        $drone->status = 'booked';
        $drone->save();

        return redirect()->route('bookings.success', $booking->id);
    }

    public function success(Booking $booking)
    {
        return view('bookings.success', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())->orderBy('start_at','desc')->paginate(10);
        return view('bookings.my', compact('bookings'));
    }
}
