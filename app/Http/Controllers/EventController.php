<?php

namespace App\Http\Controllers;

use App\Models\event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = event::latest()->paginate(10);
        return view('admin.event.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Terima baik 'name'/'nama_konser' dan 'price'/'harga_per_tiket'
        $request->merge([
            'name'  => $request->input('name', $request->input('nama_konser')),
            'price' => $request->input('price', $request->input('harga_per_tiket')),
        ]);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:musik,festival,seminar',
            'price'       => 'required|numeric|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096', // Tambah webp & max 4MB
        ]);

        $imagePath = $request->file('image')?->store('events', 'public');

        event::create([
            'nama_konser'     => $validated['name'],
            'category'        => $validated['category'],
            'harga_per_tiket' => $validated['price'],
            'description'     => $validated['description'],
            'image'           => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(event $event)
    {
        return view('admin.event.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, event $event)
    {
        // 1. Sinkronisasi nama input agar cocok dengan form blade
        $request->merge([
            'name'  => $request->input('name', $request->input('nama_konser')),
            'price' => $request->input('price', $request->input('harga_per_tiket')),
        ]);

        // 2. Validasi (ditambahkan mimes webp dan max 4MB)
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:musik,festival,seminar',
            'price'       => 'required|numeric|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $imagePath = $event->image;

        // 3. Simpan gambar baru jika diunggah
        if ($request->hasFile('image')) {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }

            $imagePath = $request->file('image')->store('events', 'public');
        }

        $event->update([
            'nama_konser'     => $validated['name'],
            'category'        => $validated['category'],
            'harga_per_tiket' => $validated['price'],
            'description'     => $validated['description'],
            'image'           => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(event $event)
    {
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }
}
