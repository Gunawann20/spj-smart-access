<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $agendas = Agenda::orderBy('created_at', 'desc')->paginate(10);
        } else {
            // Karyawan hanya melihat agenda yang aktif
            $agendas = Agenda::where('status', '!=', 'closed')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('agenda.index', compact('agendas'));
    }

    public function create()
    {
        // Hanya admin yang bisa membuat agenda
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('agenda.index')->with('error', 'Anda tidak memiliki akses untuk membuat agenda.');
        }

        return view('agenda.create');
    }

    public function store(Request $request)
    {
        // Hanya admin yang bisa membuat agenda
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('agenda.index')->with('error', 'Anda tidak memiliki akses untuk membuat agenda.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after:tanggal_mulai',
            'status' => 'required|in:active,inactive,closed',
        ]);

        Agenda::create($validated);

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dibuat!');
    }

    public function show(Agenda $agenda)
    {
        $user = auth()->user();
        
        // Karyawan hanya bisa melihat agenda yang aktif
        if ($user->role === 'karyawan' && $agenda->status === 'closed') {
            return redirect()->route('agenda.index')->with('error', 'Agenda ini telah ditutup.');
        }

        // Hitung jumlah karyawan total
        $totalKaryawan = User::where('role', 'karyawan')->count();

        // Hitung stats per jenis dokumen
        $stats = [];
        $jenisDokumen = ['RKAKL', 'RAPK', 'SPJ', 'LKJ', 'LAKIP', 'RAB'];

        foreach ($jenisDokumen as $jenis) {
            if ($jenis === 'RAB') {
                $uploaded = $agenda->rabs()
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                $uploaded = $agenda->documents()
                    ->where('jenis_dokumen', $jenis)
                    ->distinct('user_id')
                    ->count('user_id');
            }

            $stats[$jenis] = [
                'uploaded' => $uploaded,
                'total' => $totalKaryawan,
                'percentage' => $totalKaryawan > 0 ? round(($uploaded / $totalKaryawan) * 100, 2) : 0,
            ];
        }

        // Untuk karyawan, tampilkan apakah mereka sudah upload
        $userDocuments = [];
        if ($user->role === 'karyawan') {
            $userDocuments = $agenda->documents()
                ->where('user_id', $user->id)
                ->pluck('jenis_dokumen')
                ->toArray();
            
            // Add RAB if user has created one for this agenda
            $hasRab = $agenda->rabs()
                ->where('user_id', $user->id)
                ->exists();
            
            if ($hasRab) {
                $userDocuments[] = 'RAB';
            }
        }

        // Untuk admin, tampilkan semua dokumen
        $documents = $user->role === 'admin' 
            ? $agenda->documents()->with('user')->paginate(10)
            : $agenda->documents()->where('user_id', $user->id)->paginate(10);

        // Load photos for this agenda
        $photos = $agenda->photos()->orderBy('created_at', 'desc')->get();

        return view('agenda.show', compact('agenda', 'stats', 'documents', 'userDocuments', 'totalKaryawan', 'photos'));
    }

    public function edit(Agenda $agenda)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('agenda.index')->with('error', 'Anda tidak memiliki akses.');
        }

        return view('agenda.edit', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('agenda.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'nullable|date|after:tanggal_mulai',
            'status' => 'required|in:active,inactive,closed',
        ]);

        $agenda->update($validated);

        return redirect()->route('agenda.show', $agenda)->with('success', 'Agenda berhasil diperbarui!');
    }

    public function destroy(Agenda $agenda)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('agenda.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $agenda->delete();

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dihapus!');
    }

    public function getDocumentsByType(Agenda $agenda)
    {
        $jenis = request()->query('jenis');
        
        if (!$jenis) {
            return response()->json(['error' => 'Jenis dokumen tidak ditentukan'], 400);
        }

        $documents = $agenda->documents()
            ->where('jenis_dokumen', $jenis)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'nama_dokumen' => $doc->nama_dokumen,
                    'jenis_dokumen' => $doc->jenis_dokumen,
                    'user_name' => $doc->user->name,
                    'status' => $doc->status,
                    'ukuran_file' => $doc->ukuran_file,
                    'file_path' => $doc->file_path,
                    'created_at' => $doc->created_at->format('d M Y H:i'),
                ];
            });

        return response()->json([
            'jenis' => $jenis,
            'documents' => $documents,
        ]);
    }
}
