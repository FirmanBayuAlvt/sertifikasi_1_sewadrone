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
        return view('drones.show', compact('drone'));
    }
}
