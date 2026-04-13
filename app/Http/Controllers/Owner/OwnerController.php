<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePackage;
use App\Models\Transaction;
use Illuminate\Http\Request;
  use App\Models\ActivityLog; 

class OwnerController extends Controller
{
    public function courses() {
        $courses = Course::all();
        return view('owner.courses', compact('courses'));
    }

  public function packages() {
    // Ubah 'course' menjadi 'courses' sesuai nama function di model CoursePackage
    $packages = CoursePackage::with('courses')->get();
    return view('owner.packages', compact('packages'));
}



public function logs(Request $request) 
{
    // Mengambil data dari tabel activity_logs dengan relasi user
    $query = \App\Models\ActivityLog::with('user');

    // Filter berdasarkan Role User
    if ($request->role) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('role', $request->role);
        });
    }

    // Filter berdasarkan Tanggal
    if ($request->date) {
        $query->whereDate('created_at', $request->date);
    }

    $logs = $query->latest('created_at')->paginate(15)->withQueryString();
    
    return view('owner.logs', compact('logs'));
}   
}