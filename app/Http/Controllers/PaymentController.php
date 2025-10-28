<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Admin: list payments
    public function index()
    {
        $payments = Payment::with('booking.drone')->orderBy('paid_at','desc')->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    // Refund stub (manual)
    public function refund(Payment $payment)
    {
        // In real system: call payment gateway API to refund.
        $payment->status = 'refunded';
        $payment->save();

        // Update booking if needed
        if ($payment->booking) {
            $payment->booking->status = 'cancelled';
            $payment->booking->save();
        }

        return back()->with('success', 'Payment di-refund (stub).');
    }
}
