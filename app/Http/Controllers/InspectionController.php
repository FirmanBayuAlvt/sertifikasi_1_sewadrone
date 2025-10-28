<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Booking;
use App\Models\Drone;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function preflight(Booking $booking, Request $request)
    {
        $data = $request->validate([
            'preflight_ok' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $ins = Inspection::updateOrCreate(
            ['booking_id' => $booking->id],
            ['preflight_ok' => (bool)$data['preflight_ok'], 'notes' => $data['notes'] ?? null]
        );

        if ($ins->preflight_ok) {
            // optional: mark booking ongoing
            $booking->status = 'ongoing';
            $booking->save();
        }

        return back()->with('success', 'Preflight tercatat.');
    }

    public function postflight(Booking $booking, Request $request)
    {
        $data = $request->validate([
            'postflight_ok' => 'required|boolean',
            'notes' => 'nullable|string',
            'damage_cost' => 'nullable|numeric|min:0',
        ]);

        $ins = Inspection::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'postflight_ok' => (bool)$data['postflight_ok'],
                'notes' => $data['notes'] ?? null,
                'damage_cost' => $data['damage_cost'] ?? 0,
            ]
        );

        // If there is damage cost, deduct deposit and set appropriate status
        if (($data['damage_cost'] ?? 0) > 0) {
            // business logic to charge customer or deduct deposit goes here
            $booking->status = 'completed';
            $booking->save();
        } else {
            $booking->status = 'completed';
            $booking->save();
        }

        // Set drone available
        $drone = $booking->drone;
        if ($drone) {
            $drone->status = 'available';
            $drone->save();
        }

        return back()->with('success', 'Postflight tercatat.');
    }
}
