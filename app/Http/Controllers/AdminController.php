<?php

namespace App\Http\Controllers;

use App\Models\Drone;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $totalDrones = Drone::count();
        $bookingsPending = Booking::where('status','pending')->count();
        $bookingsConfirmed = Booking::where('status','confirmed')->count();

        return view('admin.dashboard', compact('totalDrones','bookingsPending','bookingsConfirmed'));
    }

    // Drone CRUD
    public function dronesIndex()
    {
        $drones = Drone::orderBy('created_at','desc')->paginate(12);
        return view('admin.drones.index', compact('drones'));
    }

    public function dronesCreate()
    {
        return view('admin.drones.create');
    }

    public function dronesStore(Request $request)
    {
        $data = $request->validate([
            'model' => 'required|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'specs' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('drones','public');
            $data['image'] = $path;
        }

        // allow specs as JSON string (client may send), try decode
        if (!empty($data['specs'])) {
            $json = json_decode($data['specs'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['specs'] = $json;
            } else {
                // simple parse: key:value per line
                $arr = [];
                foreach (preg_split("/\r\n|\n|\r/", $data['specs']) as $line) {
                    if (strpos($line, ':') !== false) {
                        [$k,$v] = array_map('trim', explode(':', $line, 2));
                        $arr[$k] = $v;
                    }
                }
                $data['specs'] = $arr;
            }
        }

        Drone::create($data);

        return redirect()->route('admin.drones.index')->with('success','Drone berhasil ditambahkan.');
    }

    public function dronesEdit(Drone $drone)
    {
        return view('admin.drones.edit', compact('drone'));
    }

    public function dronesUpdate(Request $request, Drone $drone)
    {
        $data = $request->validate([
            'model' => 'required|string|max:255',
            'serial_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'specs' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('drones','public');
            $data['image'] = $path;
        }

        if (!empty($data['specs'])) {
            $json = json_decode($data['specs'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['specs'] = $json;
            } else {
                $arr = [];
                foreach (preg_split("/\r\n|\n|\r/", $data['specs']) as $line) {
                    if (strpos($line, ':') !== false) {
                        [$k,$v] = array_map('trim', explode(':', $line, 2));
                        $arr[$k] = $v;
                    }
                }
                $data['specs'] = $arr;
            }
        }

        $drone->update($data);

        return redirect()->route('admin.drones.index')->with('success','Drone diupdate.');
    }

    public function dronesDestroy(Drone $drone)
    {
        $drone->delete();
        return back()->with('success','Drone dihapus.');
    }

    // Booking list for admin
    public function bookingsIndex()
    {
        $bookings = Booking::with('drone','user')->orderBy('start_at','desc')->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }
}
