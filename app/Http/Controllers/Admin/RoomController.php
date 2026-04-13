<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $rooms = $query->latest()->paginate(10)->withQueryString();

        return view('admin.room.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.room.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
        ]);

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('admin.room.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name'     => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
        ]);

        $room->update($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diupdate.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with('success', 'Ruangan berhasil dihapus.');
    }
}