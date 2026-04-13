<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\CoursePackage;
use App\Models\CourseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
{
    $query = Course::with('mentor');

    // Filter Nama Kursus (Search)
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter Mentor
    if ($request->filled('mentor_id')) {
        $query->where('mentor_id', $request->mentor_id);
    }

    // Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $courses = $query->latest()->paginate(10)->withQueryString();
    // Ambil data mentor untuk dropdown filter
    $mentors = \App\Models\Mentor::all(); 

    return view('admin.courses.index', compact('courses', 'mentors'));
}

    public function create()
    {
        $mentors = Mentor::all();
        return view('admin.courses.create', compact('mentors'));
    }

   public function store(Request $request)
{
    DB::beginTransaction();

    try {

        // ✅ Upload image dulu
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        } else {
            $imagePath = null;
        }

        // ✅ Create course SEKALI saja
        $course = Course::create([
            'mentor_id' => $request->mentor_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'image' => $imagePath
        ]);

        // ✅ Baru handle packages
        if ($request->packages) {
            foreach ($request->packages as $pkg) {

                $package = CoursePackage::create([
                    'name' => $pkg['name'],
                    'duration_in_month' => $pkg['duration'],
                    'session_count' => $pkg['session'],
                    'price' => $pkg['price'],
                ]);

                // Hubungkan course utama
                CourseItem::create([
                    'course_package_id' => $package->id,
                    'course_id' => $course->id
                ]);

                // Bundling course lain
                if (!empty($pkg['courses'])) {
                    foreach ($pkg['courses'] as $courseId) {
                        if ($courseId != $course->id) {
                            CourseItem::create([
                                'course_package_id' => $package->id,
                                'course_id' => $courseId
                            ]);
                        }
                    }
                }
            }
        }

        logActivity('CREATE COURSE', 'Tambah course: '.$course->name);

        DB::commit();
        return redirect()->route('admin.courses.index')->with('success', 'Berhasil simpan data');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors($e->getMessage());
    }
}

    public function edit(Course $course)
    {
        $mentors = Mentor::all();
        // Mengambil paket yang terhubung dengan course ini melalui pivot
        $packages = $course->packages()->with('items')->get();

        return view('admin.courses.edit', compact('course','mentors','packages'));
    }

    public function update(Request $request, Course $course)
    {
        DB::beginTransaction();

        try {
            $course->update($request->only(['mentor_id', 'name', 'description', 'status']));

            // Ambil ID paket yang lama terkait course ini
            $oldPackageIds = CourseItem::where('course_id', $course->id)->pluck('course_package_id');
            
            // Hapus data di pivot dan tabel paket (Opsional: sesuaikan jika paket bisa dipakai course lain)
            CourseItem::whereIn('course_package_id', $oldPackageIds)->delete();
            CoursePackage::whereIn('id', $oldPackageIds)->delete();

            $imagePath = $course->image;

if ($request->hasFile('image')) {
    // hapus lama (optional)
    if ($course->image && \Storage::disk('public')->exists($course->image)) {
        \Storage::disk('public')->delete($course->image);
    }

    $imagePath = $request->file('image')->store('courses', 'public');
}

$course->update([
    'mentor_id' => $request->mentor_id,
    'name' => $request->name,
    'description' => $request->description,
    'status' => $request->status,
    'image' => $imagePath
]);

            if ($request->packages) {
                foreach ($request->packages as $pkg) {
                    $package = CoursePackage::create([
                        'name' => $pkg['name'],
                        'duration_in_month' => $pkg['duration'],
                        'session_count' => $pkg['session'],
                        'price' => $pkg['price'],
                    ]);

                    CourseItem::create([
                        'course_package_id' => $package->id,
                        'course_id' => $course->id
                    ]);

                    if (!empty($pkg['courses'])) {
                        foreach ($pkg['courses'] as $courseId) {
                            if($courseId != $course->id) {
                                CourseItem::create([
                                    'course_package_id' => $package->id,
                                    'course_id' => $courseId
                                ]);
                            }
                        }
                    }
                }
            }

            logActivity('UPDATE COURSE', 'Update course: '.$course->name);

            DB::commit();
            return redirect()->route('admin.courses.index')->with('success', 'Updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    public function destroy(Course $course)
    {
        // Temukan paket yang hanya dimiliki oleh course ini untuk dibersihkan
        $packageIds = CourseItem::where('course_id', $course->id)->pluck('course_package_id');
        
        CourseItem::where('course_id', $course->id)->delete();
        CoursePackage::whereIn('id', $packageIds)->delete();
        $course->delete();

        logActivity('DELETE COURSE', 'Hapus course: '.$course->name);

        return back()->with('success', 'Deleted');
    }
}