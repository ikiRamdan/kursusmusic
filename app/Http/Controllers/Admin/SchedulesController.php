<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedules;
use App\Models\Mentor;
use App\Models\Room;
use App\Models\Course;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
   public function index(Request $request)
{
    // Mengambil data untuk dropdown filter
    $mentors = Mentor::all();
    $rooms = Room::all();
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    // Query dengan Eager Loading
    $schedules = Schedules::with(['mentor', 'room', 'course'])
        // Filter berdasarkan Hari
        ->when($request->day, function ($query, $day) {
            return $query->where('day_of_week', $day);
        })
        // Filter berdasarkan Mentor
        ->when($request->mentor_id, function ($query, $mentor_id) {
            return $query->where('mentor_id', $mentor_id);
        })
        // Filter berdasarkan Ruangan
        ->when($request->room_id, function ($query, $room_id) {
            return $query->where('room_id', $room_id);
        })
        ->orderBy('day_of_week')
        ->paginate(10)
        ->withQueryString(); // Agar pagination tetap membawa nilai filter

    return view('admin.schedules.index', compact('schedules', 'mentors', 'rooms', 'days'));
}



public function getMentors($courseId)
{
    // Ambil kursus beserta mentornya
    $course = Course::with('mentor')->find($courseId);

    if ($course && $course->mentor) {
        return response()->json([
            'id'   => $course->mentor->id,
            'name' => $course->mentor->name
        ]);
    }

    return response()->json(null, 404);
}

    public function create()
    {
        $schedule = new Schedules(); // Untuk form partial
        $mentors = Mentor::all();
        $rooms = Room::all();
        $courses = Course::all();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('admin.schedules.create', compact('schedule', 'mentors', 'rooms', 'courses', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'mentor_id' => 'required',
            'room_id' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        Schedules::create($request->all());
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dibuat!');
    }

    public function edit($id)
    {
        $schedule = Schedules::findOrFail($id);
        $mentors = Mentor::all();
        $rooms = Room::all();
        $courses = Course::all();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('admin.schedules.edit', compact('schedule', 'mentors', 'rooms', 'courses', 'days'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required',
            'mentor_id' => 'required',
            'room_id' => 'required',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $schedule = Schedules::findOrFail($id);
        $schedule->update($request->all());
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy($id)
    {
        Schedules::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal dihapus!');
    }
}