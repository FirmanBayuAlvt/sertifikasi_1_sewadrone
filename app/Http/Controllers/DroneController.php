<?php

namespace App\Http\Controllers;

use App\Models\Drone;
use Illuminate\Http\Request;

class DroneController extends Controller
{
    public function index()
    {
        $drones = Drone::all();
        return view('admin.drones.index', compact('drones'));
    }

    public function show($id)
    {
        $drone = Drone::findOrFail($id);
        return view('admin.drones.show', compact('drone'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $drones = Drone::where('name', 'LIKE', "%{$query}%")->get();
        return view('admin.drones.index', compact('drones'));
    }
}