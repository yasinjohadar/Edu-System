<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rooms = Room::with(['hostel', 'beds'])->paginate(15);
        return view('admin.pages.hostel.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.pages.hostel.rooms.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(Room $room)
    {
        return view('admin.pages.hostel.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('admin.pages.hostel.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        // Implementation
    }

    public function destroy(Room $room)
    {
        // Implementation
    }
}
