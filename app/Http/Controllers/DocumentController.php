<?php

namespace App\Http\Controllers;

use App\Exports\DocumentsExport;
use App\Models\Document;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // if ($user->role === 'admin') {
        //     $documents = Document::with('user')->paginate(10);
        // } else {
        //     $documents = Document::where('user_id', $user->id)->paginate(10);
        // }
        $documents = Document::with('user')->paginate(10);

        return view('document.index', compact('documents'));
    }

    public function exportExcel()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $documents = Document::with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $documents = Document::with('user')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $fileName = 'dokumen_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new DocumentsExport($documents), $fileName);
    }

    public function create()
    {
        // $user = auth()->user();
        // $agenda_id = request()->query('agenda_id');
        // $agenda = null;
        
        // Karyawan harus upload melalui agenda
        // if ($user->role === 'karyawan' && !$agenda_id) {
        //     return redirect()->route('agenda.index')->with('info', 'Silakan pilih agenda terlebih dahulu untuk upload dokumen.');
        // }
        
        // if ($agenda_id) {
        //     $agenda = \App\Models\Agenda::find($agenda_id);
        //     if (!$agenda) {
        //         return redirect()->route('agenda.index')->withErrors(['error' => 'Agenda tidak ditemukan']);
        //     }
            
        //     // Karyawan hanya bisa upload ke agenda yang aktif
        //     if ($user->role === 'karyawan' && $agenda->status === 'closed') {
        //         return redirect()->route('agenda.index')->withErrors(['error' => 'Agenda ini sudah ditutup, tidak bisa upload lagi.']);
        //     }
        // }

        return view('document.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Karyawan harus upload ke agenda
        // if ($user->role === 'karyawan') {
        //     $validated = $request->validate([
        //         'nama_dokumen' => 'required|string|max:255',
        //         'pelaksana' => 'required|string|max:255',
        //         'kode_ro' => 'required|string|max:255',
        //         'jumlah_anggaran' => 'required|numeric|min:0',
        //         'keterangan' => 'nullable|string',
        //         'agenda_id' => 'required|exists:agendas,id',
        //     ]);
            
        //     // Cek agenda aktif
        //     $agenda = \App\Models\Agenda::find($validated['agenda_id']);
        //     if ($agenda->status === 'closed') {
        //         return redirect()->back()->withErrors(['error' => 'Agenda ini sudah ditutup.']);
        //     }
        // } else {
        //     $validated = $request->validate([
        //         'nama_dokumen' => 'required|string|max:255',
        //         'pelaksana' => 'required|string|max:255',
        //         'kode_ro' => 'required|string|max:255',
        //         'jumlah_anggaran' => 'required|numeric|min:0',
        //         'keterangan' => 'nullable|string',
        //         'agenda_id' => 'nullable|exists:agendas,id',
        //     ]);
        // }

        $validated = $request->validate([
                'nama_dokumen' => 'required|string|max:255',
                'pelaksana' => 'required|string|max:255',
                'kode_ro' => 'required|string|max:255',
                'jumlah_anggaran' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string',
                'agenda_id' => 'nullable|exists:agendas,id',
            ]);

        // Check if files are uploaded
        if (!$request->hasFile('documents') || count($request->file('documents')) === 0) {
            return back()->withErrors(['documents' => 'Silakan pilih minimal satu file untuk di-upload'])->withInput();
        }

        // Validate files
        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:51200',
        ]);

        // Upload each file
        $uploadCount = 0;
        $agendaId = $validated['agenda_id'] ?? null;
        foreach ($request->file('documents') as $file) {
            $filePath = $file->store('documents', 'public');
            Document::create([
                'user_id' => auth()->id(),
            'agenda_id' => $agendaId,
                'nama_dokumen' => $request->nama_dokumen . ' - ' . $file->getClientOriginalName(),
                'pelaksana' => $request->pelaksana,
                'kode_ro' => $request->kode_ro,
                'jumlah_anggaran' => $request->jumlah_anggaran,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'ukuran_file' => $file->getSize(),
                'keterangan' => $request->keterangan,
                'status' => 'pending',
            ]);
            $uploadCount++;
        }

        if ($agendaId) {
            return redirect()->route('agenda.show', $agendaId)->with('success', "$uploadCount file(s) berhasil di-upload!");
        }

        return redirect()->route('document.index')->with('success', "$uploadCount file(s) berhasil di-upload dan menunggu persetujuan admin!");
    }

    public function show(Document $document)
    {
        // if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->user_id) {
        //     abort(403);
        // }

        return view('document.show', compact('document'));
    }

    public function edit(Document $document)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->user_id) {
            abort(403);
        }

        // Only allow editing if rejected
        if (auth()->user()->role !== 'admin' && $document->status !== 'rejected') {
            return redirect()->route('document.index')->with('error', 'Hanya dokumen dengan status ditolak yang dapat diedit.');
        }

        return view('document.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->user_id) {
            abort(403);
        }

        // Only allow updating if rejected
        if (auth()->user()->role !== 'admin' && $document->status !== 'rejected') {
            return redirect()->route('document.index')->with('error', 'Hanya dokumen dengan status ditolak yang dapat diubah.');
        }

        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'pelaksana' => 'required|string|max:255',
            'kode_ro' => 'required|string|max:255',
            'jumlah_anggaran' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:51200',
        ]);

        $data = [
            'nama_dokumen' => $validated['nama_dokumen'],
            'pelaksana' => $validated['pelaksana'],
            'kode_ro' => $validated['kode_ro'],
            'jumlah_anggaran' => $validated['jumlah_anggaran'],
            'keterangan' => $validated['keterangan'],
            'status' => 'pending', // Reset status to pending when updated
        ];

        if ($request->hasFile('document')) {
            // Delete old file
            if (\Storage::disk('public')->exists($document->file_path)) {
                \Storage::disk('public')->delete($document->file_path);
            }
            
            $file = $request->file('document');
            $filePath = $file->store('documents', 'public');
            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['ukuran_file'] = $file->getSize();
        }

        $document->update($data);

        return redirect()->route('document.index')->with('success', 'Dokumen berhasil diperbarui dan diajukan ulang!');
    }

    public function destroy(Document $document)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->user_id) {
            abort(403);
        }

        // Only allow deletion if pending or rejected
        if (auth()->user()->role !== 'admin' && !in_array($document->status, ['pending', 'rejected'])) {
            return back()->withErrors(['error' => 'Hanya file dengan status pending atau ditolak yang dapat dihapus']);
        }

        if (\Storage::disk('public')->exists($document->file_path)) {
            \Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        return redirect()->route('document.index')->with('success', 'File berhasil dihapus!');
    }

    public function download(Document $document)
    {
        // Authorization: admin bisa download semua, user hanya file mereka sendiri
        // if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->user_id && auth()->user()->role !== 'karyawan') {
        //     abort(403);
        // }

        $filePath = storage_path('app/public/' . $document->file_path);
        
        // Check file exists
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan: ' . $document->file_path);
        }
        
        // Direct download berdasarkan file extension
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        if ($extension === 'pdf') {
            // PDF file - direct download
            return response()->download($filePath, pathinfo($document->file_path, PATHINFO_FILENAME) . '.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        } elseif ($extension === 'html') {
            // HTML file - download as attachment
            return response()->download($filePath, pathinfo($document->file_path, PATHINFO_FILENAME) . '.html', [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]);
        } else {
            // Other file types - generic download
            return response()->download($filePath);
        }
    }

    public function approve(Document $document)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $document->update([
            'status' => 'approved',
            'admin_id' => auth()->id(),
            'keterangan' => 'Disetujui',
        ]);

        // Sync with Rab status if it's an RAB document
        if ($document->rab_id) {
            $document->rab->update(['status' => 'approved']);
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'File berhasil disetujui!']);
        }

        return back()->with('success', 'File berhasil disetujui!');
    }

    public function showRejectForm(Document $document)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('document.reject-form', compact('document'));
    }

    public function reject(Request $request, Document $document)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $document->update([
            'status' => 'rejected',
            'admin_id' => auth()->id(),
            'keterangan' => $request->input('keterangan', 'Ditolak'),
        ]);

        // Sync with Rab status if it's an RAB document
        if ($document->rab_id) {
            $document->rab->update(['status' => 'rejected']);
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'File berhasil ditolak!']);
        }

        return back()->with('success', 'File berhasil ditolak!');
    }

    public function saveVerification(Request $request, Document $document)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_verifikator' => 'required|string|max:255',
        ]);

        $document->update([
            'nama_verifikator' => $validated['nama_verifikator'],
        ]);

        return back()->with('success', 'Data verifikasi berhasil disimpan.');
    }

    public function saveSp2d(Request $request, Document $document)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $document->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'tanggal_sp2d' => 'required|date',
            'jumlah_anggaran_sp2d' => 'required|numeric|min:0',
        ]);

        $document->update([
            'tanggal_sp2d' => $validated['tanggal_sp2d'],
            'jumlah_anggaran_sp2d' => $validated['jumlah_anggaran_sp2d'],
        ]);

        return back()->with('success', 'Data SP2D berhasil disimpan.');
    }
}
