<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePackage;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursePackageController extends Controller
{
    public function index(Request $request)
{
    $query = CoursePackage::with('courses');

    // Pencarian berdasarkan NAMA PAKET
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter berdasarkan KURSUS yang ada di dalam paket
    if ($request->filled('course_id')) {
        $query->whereHas('courses', function($q) use ($request) {
            $q->where('courses.id', $request->course_id);
        });
    }

    $packages = $query->latest()->paginate(10)->withQueryString();
    $courses = Course::all();

    return view('admin.course_packages.index', compact('packages', 'courses'));
}

    public function create()
    {
        $courses = Course::all();
        return view('admin.course_packages.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_ids'   => 'required|array|min:1', // Validasi input array
            'course_ids.*' => 'exists:courses,id',    // Pastikan tiap ID ada di tabel courses
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'duration_in_month' => 'nullable|integer',
            'session_count'     => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan data utama ke tabel course_packages
            $pkg = CoursePackage::create($request->only([
                'name', 'duration_in_month', 'session_count', 'price'
            ]));

            // 2. Gunakan attach() untuk mengisi tabel pivot secara massal
            $pkg->courses()->attach($request->course_ids);

            logActivity('CREATE PACKAGE', 'Tambah package: ' . $pkg->name);

            DB::commit();
            return redirect()->route('admin.course-packages.index')
                ->with('success', 'Package berhasil ditambahkan dengan ' . count($request->course_ids) . ' kursus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('Gagal menambah paket: ' . $e->getMessage());
        }
    }

    public function edit(CoursePackage $course_package)
    {
        $courses = Course::all();
        // Ambil daftar ID kursus yang sudah terpilih untuk ditampilkan di view
        $selectedCourseIds = $course_package->courses->pluck('id')->toArray();
        
        return view('admin.course_packages.edit', compact('course_package', 'courses', 'selectedCourseIds'));
    }

    public function update(Request $request, CoursePackage $course_package)
    {
        $request->validate([
            'course_ids'   => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data utama
            $course_package->update($request->only([
                'name', 'duration_in_month', 'session_count', 'price'
            ]));

            // 2. Gunakan sync(): Ini akan otomatis menghapus yang lama dan menambah yang baru
            // sehingga relasi di tabel pivot selalu sinkron dengan pilihan user.
            $course_package->courses()->sync($request->course_ids);

            logActivity('UPDATE PACKAGE', 'Update package: ' . $course_package->name);

            DB::commit();
            return redirect()->route('admin.course-packages.index')
                ->with('success', 'Package berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('Gagal update paket: ' . $e->getMessage());
        }
    }

    public function destroy(CoursePackage $course_package)
    {
        DB::beginTransaction();
        try {
            $name = $course_package->name;
            
            // Putuskan relasi di tabel pivot terlebih dahulu
            $course_package->courses()->detach();
            
            // Hapus data utama
            $course_package->delete();

            logActivity('DELETE PACKAGE', 'Hapus package: ' . $name);

            DB::commit();
            return back()->with('success', 'Package berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus paket: ' . $e->getMessage());
        }
    }
}