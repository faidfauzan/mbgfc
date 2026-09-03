<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // === SISI CAPTAIN ===

    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        Announcement::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'dibuat_oleh' => auth()->id(), // Otomatis mengambil ID Captain yang sedang login
        ]);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $announcement->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus!');
    }

    // === SISI MEMBER ===

    public function memberIndex()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('announcements.member-index', compact('announcements'));
    }
}