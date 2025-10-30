<?php

namespace App\Http\Controllers;

use App\Models\Drone;
use Illuminate\Http\Request;

class DroneController extends Controller
{
    public function index(Request $request)
    {
        $query = Drone::query();

        if ($request->q) {
            $query->where('model', 'like', '%' . $request->q . '%')
                ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        $drones = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('drones.index', compact('drones'));
    }

    public function show(Drone $drone)
    {
        // ambil unit yg statusnya available (jika relasi units ada)
        $availableUnits = collect();
        try {
            if (method_exists($drone, 'units')) {
                $availableUnits = $drone->units()->where('status', 'available')->get();
            }
        } catch (\Throwable $e) {
            // kalau relasi/drone_units belum ada jangan crash — keep empty collection
            $availableUnits = collect();
        }

        return view('drones.show', compact('drone', 'availableUnits'));
    }
}
