<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Agenda;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a photo.
     */
    public function show(Photo $photo)
    {
        // Check if file exists
        if (!\Storage::disk('public')->exists($photo->file_path)) {
            abort(404, 'Photo not found');
        }

        // Get file content
        $content = \Storage::disk('public')->get($photo->file_path);
        
        // Get file extension for mime type
        $extension = pathinfo($photo->file_path, PATHINFO_EXTENSION);
        $mimeType = match($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/jpeg'
        };

        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Store a newly created photo in storage.
     */
    public function store(Request $request, Agenda $agenda)
    {
        // Only admin can upload photos
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Hanya admin yang dapat upload foto dokumentasi.');
        }

        $validated = $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->file('foto')) {
            // Store the image in storage/app/public/photos/agendas/
            $filename = time() . '_' . uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
            $path = $request->file('foto')->storeAs('photos/agendas', $filename, 'public');

            // Create photo record
            Photo::create([
                'agenda_id' => $agenda->id,
                'file_path' => $path,
                'file_name' => $request->file('foto')->getClientOriginalName(),
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            return back()->with('success', 'Foto dokumentasi berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload foto.');
    }

    /**
     * Delete a photo.
     */
    public function destroy(Photo $photo)
    {
        // Only admin can delete photos
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Hanya admin yang dapat menghapus foto.');
        }

        // Check if user is the owner of this agenda (optional security check)
        if (auth()->user()->role === 'admin') {
            // Delete file from storage
            if (\Storage::disk('public')->exists($photo->file_path)) {
                \Storage::disk('public')->delete($photo->file_path);
            }

            $photo->delete();
            return back()->with('success', 'Foto berhasil dihapus.');
        }

        return back()->with('error', 'Tidak diizinkan menghapus foto ini.');
    }
}
