<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use App\Models\RabItem;
use App\Models\Agenda;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class RabController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $rabs = Rab::with('user', 'agenda')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $rabs = Rab::where('user_id', $user->id)->with('agenda')->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('rab.index', compact('rabs'));
    }

    public function create(Request $request)
    {
        $agendas = Agenda::where('status', 'active')->get();
        $selectedAgendaId = $request->query('agenda_id');
        return view('rab.create', compact('agendas', 'selectedAgendaId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_rab' => 'required|string|max:255',
            'tanggal_rab' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'tempat_pelaksanaan' => 'nullable|string|max:255',
            'sumber_kegiatan' => 'nullable|string',
            'sumber_anggaran' => 'nullable|string|max:255',
            'akun_yang_digunakan' => 'nullable|string|max:255',
            'tahun_anggaran' => 'required|numeric|min:2020|max:2099',
            'keterangan_rab' => 'nullable|string',
            'agenda_id' => 'nullable|exists:agendas,id',
            'nama_pemoton' => 'nullable|string|max:255',
            'nama_direktur' => 'nullable|string|max:255',
            'nama_pejabat' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.uraian' => 'required|string',
            'items.*.volume' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.potongan_50_persen' => 'nullable|numeric|min:0',
            'items.*.surat_undangan' => 'nullable|boolean',
            'items.*.kak' => 'nullable|boolean',
            'items.*.materi' => 'nullable|boolean',
            'items.*.notulen' => 'nullable|boolean',
            'items.*.absen' => 'nullable|boolean',
            'items.*.kuitansi' => 'nullable|boolean',
            'items.*.surat_tugas' => 'nullable|boolean',
            'items.*.laporan' => 'nullable|boolean',
            'items.*.ktp' => 'nullable|boolean',
            'items.*.npwp' => 'nullable|boolean',
        ]);

        // Hitung total jumlah
        $total_jumlah = 0;
        foreach ($request->items as $item) {
            $total_jumlah += $item['volume'] * $item['harga_satuan'];
        }

        // Generate nomor_rab otomatis
        $year = date('Y');
        $count = Rab::whereYear('created_at', $year)->count() + 1;
        $nomor_rab = 'RAB-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Buat RAB
        $rab = Rab::create([
            'user_id' => auth()->id(),
            'agenda_id' => $request->agenda_id,
            'judul_rab' => $validated['judul_rab'],
            'nomor_rab' => $nomor_rab,
            'tanggal_rab' => $validated['tanggal_rab'],
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'tempat_pelaksanaan' => $validated['tempat_pelaksanaan'] ?? null,
            'sumber_kegiatan' => $validated['sumber_kegiatan'] ?? null,
            'sumber_anggaran' => $validated['sumber_anggaran'] ?? null,
            'akun_yang_digunakan' => $validated['akun_yang_digunakan'] ?? null,
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'keterangan_rab' => $validated['keterangan_rab'],
            'total_jumlah' => $total_jumlah,
            'nama_pemoton' => $validated['nama_pemoton'] ?? null,
            'nama_direktur' => $validated['nama_direktur'] ?? null,
            'nama_pejabat' => $validated['nama_pejabat'] ?? null,
            'status' => $request->submit_action === 'submit' ? 'submitted' : 'draft',
        ]);

        // Buat items RAB
        foreach ($request->items as $index => $item) {
            $jumlah = $item['volume'] * $item['harga_satuan'];
            $potongan = isset($item['potongan_50_persen']) ? floatval($item['potongan_50_persen']) : 0;
            
            RabItem::create([
                'rab_id' => $rab->id,
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => 'unit',
                'harga_satuan' => $item['harga_satuan'],
                'jumlah' => $jumlah,
                'potongan_50_persen' => $potongan,
                'pajak' => 0,
                'jumlah_pasca_pajak' => 0,
                'surat_undangan' => isset($item['surat_undangan']) && $item['surat_undangan'] ? 1 : 0,
                'kak' => isset($item['kak']) && $item['kak'] ? 1 : 0,
                'materi' => isset($item['materi']) && $item['materi'] ? 1 : 0,
                'notulen' => isset($item['notulen']) && $item['notulen'] ? 1 : 0,
                'absen' => isset($item['absen']) && $item['absen'] ? 1 : 0,
                'kuitansi' => isset($item['kuitansi']) && $item['kuitansi'] ? 1 : 0,
                'surat_tugas' => isset($item['surat_tugas']) && $item['surat_tugas'] ? 1 : 0,
                'laporan' => isset($item['laporan']) && $item['laporan'] ? 1 : 0,
                'ktp' => isset($item['ktp']) && $item['ktp'] ? 1 : 0,
                'npwp' => isset($item['npwp']) && $item['npwp'] ? 1 : 0,
            ]);
        }

        // Generate PDF file and Document record only if submitted
        if ($request->submit_action === 'submit') {
            try {
                $pdfData = $this->generatePdfFile($rab);
                
                // Create Document record
                Document::create([
                    'user_id' => auth()->id(),
                    'agenda_id' => $rab->agenda_id,
                    'nama_dokumen' => 'RAB - ' . $rab->judul_rab,
                    'jenis_dokumen' => 'rab',
                    'file_path' => $pdfData['filePath'],
                    'file_type' => 'application/pdf',
                    'ukuran_file' => $pdfData['fileSize'],
                    'status' => 'pending',
                    'tahun' => $rab->tahun_anggaran,
                    'keterangan' => 'File RAB otomatis dari form input - ' . $rab->nomor_rab,
                    'rab_id' => $rab->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error generating RAB PDF file: ' . $e->getMessage());
            }
        }

        return redirect()->route('rab.show', $rab)->with('success', 'RAB berhasil dibuat! File PDF telah disimpan.');
    }

    public function show(Rab $rab)
    {
        $rab->load('user', 'agenda', 'items');
        return view('rab.show', compact('rab'));
    }

    public function edit(Rab $rab)
    {
        if (auth()->id() !== $rab->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $agendas = Agenda::where('status', 'active')->get();
        $rab->load('items');
        return view('rab.edit', compact('rab', 'agendas'));
    }

    public function update(Request $request, Rab $rab)
    {
        if (auth()->id() !== $rab->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'judul_rab' => 'required|string|max:255',
            'tanggal_rab' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'tempat_pelaksanaan' => 'nullable|string|max:255',
            'sumber_kegiatan' => 'nullable|string',
            'jenis_kegiatan' => 'nullable|in:rapat,online,workshop,pelatihan,lainnya',
            'akun_yang_digunakan' => 'nullable|string|max:255',
            'tahun_anggaran' => 'required|numeric|min:2020|max:2099',
            'keterangan_rab' => 'nullable|string',
            'agenda_id' => 'nullable|exists:agendas,id',
            'nama_pemoton' => 'nullable|string|max:255',
            'nama_direktur' => 'nullable|string|max:255',
            'nama_pejabat' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.uraian' => 'required|string',
            'items.*.volume' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.potongan_50_persen' => 'nullable|numeric|min:0',
            'items.*.surat_undangan' => 'nullable|boolean',
            'items.*.kak' => 'nullable|boolean',
            'items.*.materi' => 'nullable|boolean',
            'items.*.notulen' => 'nullable|boolean',
            'items.*.absen' => 'nullable|boolean',
            'items.*.kuitansi' => 'nullable|boolean',
            'items.*.surat_tugas' => 'nullable|boolean',
            'items.*.laporan' => 'nullable|boolean',
            'items.*.ktp' => 'nullable|boolean',
            'items.*.npwp' => 'nullable|boolean',
        ]);

        // Hitung total
        $total_jumlah = 0;
        foreach ($request->items as $item) {
            $total_jumlah += $item['volume'] * $item['harga_satuan'];
        }

        // Update RAB (keep existing nomor_rab)
        $rab->update([
            'judul_rab' => $validated['judul_rab'],
            'tanggal_rab' => $validated['tanggal_rab'],
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'tempat_pelaksanaan' => $validated['tempat_pelaksanaan'] ?? null,
            'sumber_kegiatan' => $validated['sumber_kegiatan'] ?? null,
            'sumber_anggaran' => $validated['sumber_anggaran'] ?? null,
            'akun_yang_digunakan' => $validated['akun_yang_digunakan'] ?? null,
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'keterangan_rab' => $validated['keterangan_rab'],
            'agenda_id' => $request->agenda_id,
            'total_jumlah' => $total_jumlah,
            'nama_pemoton' => $validated['nama_pemoton'] ?? null,
            'nama_direktur' => $validated['nama_direktur'] ?? null,
            'nama_pejabat' => $validated['nama_pejabat'] ?? null,
            'status' => $request->submit_action === 'submit' ? 'submitted' : 'draft',
        ]);

        // Hapus items lama dan buat baru
        $rab->items()->delete();
        foreach ($request->items as $item) {
            $jumlah = $item['volume'] * $item['harga_satuan'];
            $potongan = isset($item['potongan_50_persen']) ? floatval($item['potongan_50_persen']) : 0;
            
            RabItem::create([
                'rab_id' => $rab->id,
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => 'unit',
                'harga_satuan' => $item['harga_satuan'],
                'jumlah' => $jumlah,
                'potongan_50_persen' => $potongan,
                'pajak' => 0,
                'jumlah_pasca_pajak' => 0,
                'surat_undangan' => isset($item['surat_undangan']) && $item['surat_undangan'] ? 1 : 0,
                'kak' => isset($item['kak']) && $item['kak'] ? 1 : 0,
                'materi' => isset($item['materi']) && $item['materi'] ? 1 : 0,
                'notulen' => isset($item['notulen']) && $item['notulen'] ? 1 : 0,
                'absen' => isset($item['absen']) && $item['absen'] ? 1 : 0,
                'kuitansi' => isset($item['kuitansi']) && $item['kuitansi'] ? 1 : 0,
                'surat_tugas' => isset($item['surat_tugas']) && $item['surat_tugas'] ? 1 : 0,
                'laporan' => isset($item['laporan']) && $item['laporan'] ? 1 : 0,
                'ktp' => isset($item['ktp']) && $item['ktp'] ? 1 : 0,
                'npwp' => isset($item['npwp']) && $item['npwp'] ? 1 : 0,
            ]);
        }

        // Generate PDF file and Document record only if submitted
        if ($request->submit_action === 'submit') {
            try {
                $pdfData = $this->generatePdfFile($rab);
                
                // Update or create Document record
                $doc = Document::where('rab_id', $rab->id)->first();
                if ($doc) {
                    if (Storage::disk('public')->exists($doc->file_path)) {
                        Storage::disk('public')->delete($doc->file_path);
                    }
                    $doc->update([
                        'file_path' => $pdfData['filePath'],
                        'ukuran_file' => $pdfData['fileSize'],
                        'status' => 'pending', // Reset status to pending when updated and re-submitted
                        'keterangan' => 'File RAB otomatis dari form input (diperbarui) - ' . $rab->nomor_rab,
                    ]);
                } else {
                    Document::create([
                        'user_id' => auth()->id(),
                        'agenda_id' => $rab->agenda_id,
                        'nama_dokumen' => 'RAB - ' . $rab->judul_rab,
                        'jenis_dokumen' => 'rab',
                        'file_path' => $pdfData['filePath'],
                        'file_type' => 'application/pdf',
                        'ukuran_file' => $pdfData['fileSize'],
                        'status' => 'pending',
                        'tahun' => $rab->tahun_anggaran,
                        'keterangan' => 'File RAB otomatis dari form input - ' . $rab->nomor_rab,
                        'rab_id' => $rab->id,
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Error generating RAB PDF file: ' . $e->getMessage());
            }
        }

        return redirect()->route('rab.show', $rab)->with('success', 'RAB berhasil diperbarui! File PDF telah disimpan.');
    }

    public function destroy(Rab $rab)
    {
        if (auth()->id() !== $rab->user_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Find and delete associated Document
        $doc = Document::where('file_path', 'like', '%' . $rab->nomor_rab . '%')->first();
        if ($doc) {
            // Delete file
            if (Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }
            $doc->delete();
        }

        $rab->delete();
        return redirect()->route('rab.index')->with('success', 'RAB berhasil dihapus!');
    }

    // Create RAB form khusus untuk upload ke agenda
    public function createForAgenda(Agenda $agenda)
    {
        // Cek akses - hanya karyawan atau admin yang bisa upload
        if (auth()->user()->role !== 'karyawan' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Cek agenda status
        if ($agenda->status === 'closed') {
            return redirect()->route('agenda.show', $agenda)->with('error', 'Agenda ini telah ditutup.');
        }

        return view('rab.upload-for-agenda', compact('agenda'));
    }

    // Store RAB untuk agenda spesifik
    public function storeForAgenda(Request $request, Agenda $agenda)
    {
        // Cek akses
        if (auth()->user()->role !== 'karyawan' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Cek agenda status
        if ($agenda->status === 'closed') {
            return redirect()->route('agenda.show', $agenda)->with('error', 'Agenda ini telah ditutup.');
        }

        $validated = $request->validate([
            'judul_rab' => 'required|string|max:255',
            'tanggal_rab' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'tempat_pelaksanaan' => 'nullable|string|max:255',
            'sumber_kegiatan' => 'nullable|string',
            'sumber_anggaran' => 'nullable|string|max:255',
            'akun_yang_digunakan' => 'nullable|string|max:255',
            'tahun_anggaran' => 'required|numeric|min:2020|max:2099',
            'keterangan_rab' => 'nullable|string',
            'nama_pemoton' => 'nullable|string|max:255',
            'nama_direktur' => 'nullable|string|max:255',
            'nama_pejabat' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.uraian' => 'required|string',
            'items.*.volume' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.potongan_50_persen' => 'nullable|numeric|min:0',
        ]);

        // Hitung total jumlah
        $total_jumlah = 0;
        foreach ($request->items as $item) {
            $total_jumlah += $item['volume'] * $item['harga_satuan'];
        }

        // Generate nomor_rab otomatis
        $year = date('Y');
        $count = Rab::whereYear('created_at', $year)->count() + 1;
        $nomor_rab = 'RAB-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Buat RAB dengan agenda_id dari route parameter
        $rab = Rab::create([
            'user_id' => auth()->id(),
            'agenda_id' => $agenda->id,
            'judul_rab' => $validated['judul_rab'],
            'nomor_rab' => $nomor_rab,
            'tanggal_rab' => $validated['tanggal_rab'],
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'tempat_pelaksanaan' => $validated['tempat_pelaksanaan'] ?? null,
            'sumber_kegiatan' => $validated['sumber_kegiatan'] ?? null,
            'akun_yang_digunakan' => $validated['akun_yang_digunakan'] ?? null,
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'keterangan_rab' => $validated['keterangan_rab'],
            'total_jumlah' => $total_jumlah,
            'nama_pemoton' => $validated['nama_pemoton'] ?? null,
            'nama_direktur' => $validated['nama_direktur'] ?? null,
            'nama_pejabat' => $validated['nama_pejabat'] ?? null,
            'status' => $request->submit_action === 'submit' ? 'submitted' : 'draft',
        ]);

        // Buat items RAB
        foreach ($request->items as $index => $item) {
            $jumlah = $item['volume'] * $item['harga_satuan'];
            $potongan = isset($item['potongan_50_persen']) ? floatval($item['potongan_50_persen']) : 0;
            
            RabItem::create([
                'rab_id' => $rab->id,
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => 'unit',
                'harga_satuan' => $item['harga_satuan'],
                'jumlah' => $jumlah,
                'potongan_50_persen' => $potongan,
                'pajak' => 0,
                'jumlah_pasca_pajak' => 0,
                'surat_undangan' => 0,
                'kak' => 0,
                'materi' => 0,
                'notulen' => 0,
                'absen' => 0,
                'kuitansi' => 0,
                'surat_tugas' => 0,
                'laporan' => 0,
                'ktp' => 0,
                'npwp' => 0,
            ]);
        }

        // Generate PDF file and Document record only if submitted
        if ($request->submit_action === 'submit') {
            try {
                $pdfData = $this->generatePdfFile($rab);
                
                // Create Document record
                Document::create([
                    'user_id' => auth()->id(),
                    'agenda_id' => $agenda->id,
                    'nama_dokumen' => 'RAB - ' . $rab->judul_rab,
                    'jenis_dokumen' => 'rab',
                    'file_path' => $pdfData['filePath'],
                    'file_type' => 'application/pdf',
                    'ukuran_file' => $pdfData['fileSize'],
                    'status' => 'pending',
                    'tahun' => $rab->tahun_anggaran,
                    'keterangan' => 'File RAB otomatis dari form input - ' . $rab->nomor_rab,
                    'rab_id' => $rab->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error generating RAB PDF file: ' . $e->getMessage());
            }
        }

        return redirect()->route('agenda.show', $agenda)->with('success', 'RAB berhasil diupload ke agenda! File PDF telah disimpan.');
    }

    /**
     * Generate PDF from RAB data
     */
    private function generatePdfFile($rab)
    {
        try {
            $fileName = $rab->nomor_rab . '_' . date('YmdHis') . '.pdf';
            $directory = 'documents/rab';
            $relativePath = $directory . '/' . $fileName;
            
            // Create directory if not exists
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // Render Blade view to HTML
            $htmlContent = View::make('rab.pdf', ['rab' => $rab])->render();
            
            // Generate PDF using mPDF
            $mpdf = new Mpdf([
                'tempDir' => storage_path('app/temp'),
                'orientation' => 'P',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
            ]);
            
            // Create temp directory if not exists
            if (!is_dir(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            // Write HTML to PDF
            $mpdf->WriteHTML($htmlContent);
            
            // Output PDF to file
            $fullPath = storage_path('app/public/' . $relativePath);
            $mpdf->Output($fullPath, 'F');
            
            // Verify file was created
            if (!file_exists($fullPath)) {
                throw new \Exception('Failed to create PDF file');
            }
            
            return [
                'fileName' => $fileName,
                'filePath' => $relativePath,
                'fileSize' => filesize($fullPath),
            ];
        } catch (\Exception $e) {
            \Log::error('Error generating PDF file: ' . $e->getMessage());
            throw $e;
        }
    }
}
