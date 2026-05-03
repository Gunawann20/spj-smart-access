<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Document;
use Illuminate\Http\Request;

class FolderController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $folders = Folder::with('user')->paginate(10);
        } else {
            $folders = Folder::where('user_id', $user->id)->paginate(10);
        }

        return view('folder.index', compact('folders'));
    }

    public function create()
    {
        return view('folder.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_folder' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_dokumen' => 'required|in:RKAKL,RAPK,SPJ,LKJ,LAKIP',
            'tahun' => 'required|numeric|min:2020|max:2099',
        ]);

        // Check if files are uploaded
        if (!$request->hasFile('documents') || count($request->file('documents')) === 0) {
            return back()->withErrors(['documents' => 'Silakan pilih minimal satu file untuk di-upload'])->withInput();
        }

        // Validate files
        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:51200',
        ]);

        $folder = Folder::create([
            'user_id' => auth()->id(),
            'nama_folder' => $request->nama_folder,
            'deskripsi' => $request->deskripsi,
            'jenis_dokumen' => $request->jenis_dokumen,
            'status' => 'pending',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $filePath = $file->store('documents', 'public');
                Document::create([
                    'folder_id' => $folder->id,
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'ukuran_file' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('folder.index')->with('success', 'Dokumen berhasil di-upload dan menunggu persetujuan admin!');
    }

    public function show(Folder $folder)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $folder->user_id) {
            abort(403);
        }

        $documents = $folder->documents;
        return view('folder.show', compact('folder', 'documents'));
    }

    public function destroy(Folder $folder)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $folder->user_id) {
            abort(403);
        }

        $folder->delete();
        return redirect()->route('folder.index')->with('success', 'Folder berhasil dihapus!');
    }

    public function download(Document $document)
    {
        $folder = $document->folder;
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $folder->user_id) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $document->file_path));
    }
}
