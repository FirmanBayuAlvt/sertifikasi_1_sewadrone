<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Drone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function borrow(Request $request)
    {
        $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'duration' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        if ($user->rentals()->count() >= 2) {
            return response()->json(['message' => 'You can only borrow a maximum of 2 drones.'], 403);
        }

        $rental = Rental::create([
            'user_id' => $user->id,
            'drone_id' => $request->drone_id,
            'duration' => $request->duration,
            'status' => 'borrowed',
        ]);

        return response()->json($rental, 201);
    }

    public function returnDrone(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);

        if ($rental->status !== 'borrowed' || $rental->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized or invalid rental.'], 403);
        }

        $rental->status = 'returned';
        $rental->save();

        return response()->json(['message' => 'Drone returned successfully.']);
    }

    public function userRentals()
    {
        $rentals = Auth::user()->rentals()->with('drone')->get();
        return response()->json($rentals);
    }

    public function adminRentals()
    {
        $rentals = Rental::with('user', 'drone')->get();
        return response()->json($rentals);
    }
}