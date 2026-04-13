<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function index(Request $request)
    {
        $query = Mentor::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $mentors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.mentor.index', compact('mentors'));
    }

    public function create()
    {
        return view('admin.mentor.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:mentors,email',
        'phone' => 'nullable',
        'bio' => 'nullable',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->only(['name','email','phone','bio']);

    // 📸 upload foto
    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('mentors', 'public');
    }

    $mentor = Mentor::create($data);

    logActivity('CREATE MENTOR', 'Tambah mentor: '.$mentor->name);

    return redirect()->route('admin.mentors.index')
        ->with('success', 'Mentor berhasil ditambahkan');
}

    public function edit(Mentor $mentor)
    {
        return view('admin.mentor.edit', compact('mentor'));
    }

    public function update(Request $request, Mentor $mentor)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:mentors,email,' . $mentor->id,
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->only(['name','email','phone','bio']);

    if ($request->hasFile('foto')) {

        // hapus foto lama
        if ($mentor->foto) {
            Storage::disk('public')->delete($mentor->foto);
        }

        // upload baru
        $data['foto'] = $request->file('foto')->store('mentors', 'public');
    }

    $mentor->update($data);

    logActivity('UPDATE MENTOR', 'Update mentor: '.$mentor->name);

    return redirect()->route('admin.mentors.index')
        ->with('success', 'Mentor berhasil diupdate');
}

    public function destroy(Mentor $mentor)
{   
    if ($mentor->foto) {
        Storage::disk('public')->delete($mentor->foto);
    }

    $name = $mentor->name;

    $mentor->delete();

    logActivity('DELETE MENTOR', 'Hapus mentor: '.$name);

    return back()->with('success', 'Mentor berhasil dihapus');
}
}