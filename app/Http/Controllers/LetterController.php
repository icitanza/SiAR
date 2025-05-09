<?php

namespace App\Http\Controllers;

use App\DataTables\LettersDataTable;
use App\Http\Requests\LetterRequest;
use App\Models\Letter;
use App\Services\SupabaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Letter::query();
    
        // Filter berdasarkan tipe
        if ($request->filled('filterTipe')) {
            $query->where('type', $request->filterTipe);
        }
    
        // Filter berdasarkan bulan
        if ($request->filled('filterBulan')) {
            $query->whereRaw('EXTRACT(MONTH FROM letter_date) = ?', [$request->filterBulan]);
        }
    
        // Filter berdasarkan tahun
        if ($request->filled('filterTahun')) {
            $query->whereRaw('EXTRACT(YEAR FROM letter_date) = ?', [$request->filterTahun]);
        }
    
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('letter_name', 'like', '%' . $request->search . '%')
                    ->orWhere('letter_subject', 'like', '%' . $request->search . '%')
                    ->orWhere('letter_from', 'like', '%' . $request->search . '%')
                  ->orWhere('letter_send_to', 'like', '%' . $request->search . '%');
            });
        }
    
        // Ambil data dengan pagination
        $data = $query->paginate(10);
    
        // Mengirim data ke view
        return view('letter.index', compact('data'));
        // return $dataTable->render('letter.index', compact(['data', 'totalMasuk', 'totalKeluar']));
    }
    

    public function form(Request $request, $id = null)
    {
        $data = $id ? Letter::findOrFail($id) : new Letter();

        return view('letter.form', compact('data'));
    }

    public function history(Request $request)
    {
        Carbon::setLocale('id');
        $query = Letter::query();

        if ($request->filled('filterTipe')) {
            $query->where('type', $request->filterTipe);
        }
    
        if ($request->filled('filterBulan')) {
            $query->whereRaw('EXTRACT(MONTH FROM letter_date) = ?', [$request->filterBulan]);
        }        
    
        if ($request->filled('filterTahun')) {
            $query->whereRaw('EXTRACT(YEAR FROM letter_date) = ?', [$request->filterTahun]);
        }        
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('letter_from', 'like', '%' . $request->search . '%')
                  ->orWhere('letter_send_to', 'like', '%' . $request->search . '%');
            });
        }        

        $letters = $query->get();
    
        $grouped = $letters->groupBy(function ($item) {
            // Tentukan nilai kunci berdasarkan type
            $keyPart = $item->type === 'masuk' ? $item->letter_from : $item->letter_send_to;
    
            // Normalisasi: gabungkan type dan hasil normalisasi field
            $normalized = strtolower($item->type) . '-' . str_replace(' ', '', strtolower($keyPart));
    
            return $normalized;
        });
        // dd($grouped);
        return view('letter.history', compact(['grouped']));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(LetterRequest $request)
    {
        // dd($request->all());
        $request->validated();

        // if ($request->hasFile('file')) {
        //     $filePath = $request->file('file')->store('letters', 'public');
        // } else {
        //     $filePath = null;
        // }
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            // Buat nama file unik
            $uniqueName = uniqid('letter_') . '.' . $file->getClientOriginalExtension();
    
            // Path dalam bucket Supabase (misal folder "letters/")
            $storagePath = 'letters/' . $uniqueName;
    
            // Upload ke Supabase via service
            $uploadedPath = $this->supabase->uploadFile($file, $storagePath);
    
            if ($uploadedPath) {
                $filePath = $uploadedPath; // Simpan path untuk database
            } else {
                return back()->withErrors(['file' => 'Gagal mengunggah file ke Supabase.']);
            }
        }

        $letter = Letter::create([
            'letter_name' => $request->letter_name,
            'type' => $request->type,
            'letter_date' => $request->letter_date,
            'letter_from' => $request->letter_from,
            'letter_send_to' => $request->letter_send_to,
            'letter_subject' => $request->letter_subject,
            'letter_path' => $filePath,
        ]);

        // ID surat yang baru dibuat bisa diakses dengan $letter->id
        $newLetterId = $letter->id;
        
        // Update kolom link_qr setelah ID didapat
        $letter->update([
            'link_qr' => url('/qr/' . $newLetterId),
        ]);

        return redirect()->route('letter.index')->with('success', 'Surat berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function detail(Request $request, $type)
    {
        Carbon::setLocale('id');
        $query = Letter::query();
    
        if ($request->filled('filterBulan')) {
            $query->whereRaw('EXTRACT(MONTH FROM letter_date) = ?', [$request->filterBulan]);
        }
    
        if ($request->filled('filterTahun')) {
            $query->whereRaw('EXTRACT(YEAR FROM letter_date) = ?', [$request->filterTahun]);
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('letter_name', 'like', '%' . $request->search . '%')
                    ->orWhere('letter_subject', 'like', '%' . $request->search . '%')
                    ->orWhere('letter_from', 'like', '%' . $request->search . '%')
                  ->orWhere('letter_send_to', 'like', '%' . $request->search . '%');
            });
        }        

        // Gunakan pagination setelah semua filter diterapkan
        $data = $query->paginate(10)->onEachSide(5);

        return view('letter.detail', compact('data'));
    }

    public function historyDetail(Request $request, $type, $month, $year, $id)
    {

        // $data = Letter::where(['letter_from' => $id, 'type' => $type])->paginate(10)->onEachSide(5);

        // if ($data->count() == 0) 
        // {
        //     $data = Letter::where(['letter_send_to' => $id, 'type' => $type])->paginate(10)->onEachSide(5);
        // }

        // if ($type == 'masuk') {
        //     $title = Letter::where('type', $type)->whereRaw("CONCAT(?, '-', REPLACE(LOWER(letter_from), ' ', '')) = ?", [$type, $id])->first()->letter_from;
        // } else {
        //     $title = Letter::where('type', $type)->whereRaw("CONCAT(?, '-', REPLACE(LOWER(letter_send_to), ' ', '')) = ?", [$type, $id])->first()->letter_send_to;
        // }
        
        // // $title = Letter::where('type', $type)->whereRaw("CONCAT(?, '-', REPLACE(LOWER(letter_from), ' ', '')) = ?", [$type, $id])->first()->letter_from;
        
        // $data = Letter::where('type', $type)
        //     ->whereRaw("CONCAT(?, '-', REPLACE(LOWER(letter_from), ' ', '')) = ?", [$type, $id])
        //     ->paginate(10)
        //     ->onEachSide(5);

        // if ($data->count() == 0) {
        //     $data = Letter::where('type', $type)
        //         ->whereRaw("CONCAT(?, '-', REPLACE(LOWER(letter_send_to), ' ', '')) = ?", [$type, $id])
        //         ->paginate(10)
        //         ->onEachSide(5);
        // }
        // return view('letter.history-detail', compact(['data', 'title']));

        $queryBase = Letter::where('type', $type);

        // Filter bulan dan tahun jika bukan "all"
        if ($month !== 'all') {
            $queryBase->whereRaw('EXTRACT(MONTH FROM letter_date) = ?', [$month]);
        }        
    
        if ($year !== 'all') {
            $queryBase->whereRaw('EXTRACT(YEAR FROM letter_date) = ?', [$year]);
        }        
    
        if ($type === 'masuk') {
            $title = (clone $queryBase)
                ->whereRaw("CONCAT(CAST(? AS TEXT), '-', REPLACE(LOWER(letter_from), ' ', '')) = ?", [$type, $id])
                ->first()
                ->letter_from ?? 'Tidak diketahui';
        
            $data = (clone $queryBase)
                ->whereRaw("CONCAT(CAST(? AS TEXT), '-', REPLACE(LOWER(letter_from), ' ', '')) = ?", [$type, $id])
                ->paginate(10)
                ->onEachSide(5);
        
            if ($data->count() == 0) {
                $data = (clone $queryBase)
                    ->whereRaw("CONCAT(CAST(? AS TEXT), '-', REPLACE(LOWER(letter_send_to), ' ', '')) = ?", [$type, $id])
                    ->paginate(10)
                    ->onEachSide(5);
            }
        } else {
            $title = Letter::where('type', $type)
                ->whereRaw("CONCAT(CAST(? AS TEXT), '-', REPLACE(LOWER(letter_send_to), ' ', '')) = CAST(? AS TEXT)", [$type, $id])
                ->first()
                ->letter_send_to ?? 'Tidak diketahui';
        
            $data = Letter::where('type', $type)
                ->whereRaw("CONCAT(CAST(? AS TEXT), '-', REPLACE(LOWER(letter_from), ' ', '')) = ?", [$type, $id])
                ->paginate(10)
                ->onEachSide(5);
        
            if ($data->count() == 0) {
                $data = Letter::where('type', $type)
                    ->whereRaw("CONCAT(CAST(? AS TEXT), '-', REPLACE(LOWER(letter_send_to), ' ', '')) = ?", [$type, $id])
                    ->paginate(10)
                    ->onEachSide(5);
            }
        }        
    
        return view('letter.history-detail', compact(['data', 'title']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LetterRequest $request, string $id)
    {
        $request->validated();
    
        $letter = Letter::findOrFail($id);
        $filePath = $letter->letter_path; // default: path lama
    
        // if ($request->hasFile('file')) {
        //     // Hapus file lama jika ada
        //     if ($filePath && Storage::disk('public')->exists($filePath)) {
        //         Storage::disk('public')->delete($filePath);
        //     }
    
        //     // Simpan file baru
        //     $filePath = $request->file('file')->store('letters', 'public');
        // }

        if ($request->hasFile('file')) {
            // Hapus file lama dari Supabase jika ada
            if ($filePath) {
                $this->supabase->deleteFile($filePath); // Hapus file lama dari Supabase
            }
    
            // Buat nama file unik dan simpan ke Supabase
            $file = $request->file('file');
            $uniqueName = uniqid('letter_') . '.' . $file->getClientOriginalExtension();
            $storagePath = 'letters/' . $uniqueName;
    
            $uploadedPath = $this->supabase->uploadFile($file, $storagePath);
    
            if ($uploadedPath) {
                $filePath = $uploadedPath; // Simpan path baru untuk database
            } else {
                return back()->withErrors(['file' => 'Gagal mengunggah file ke Supabase.']);
            }
        }
    
        $letter->update([
            'letter_name' => $request->letter_name,
            'type' => $request->type,
            'letter_date' => $request->letter_date,
            'letter_from' => $request->letter_from,
            'letter_send_to' => $request->letter_send_to,
            'letter_subject' => $request->letter_subject,
            'letter_path' => $filePath,
            'link_qr' => url('/qr/' . $id),
        ]);
    
        return redirect()->route('letter.index')->with('success', 'Surat berhasil diperbarui');
    }

    public function download(string $id)
    {
        $letter = Letter::findOrFail($id);

        if (!$letter->letter_path) {
            abort(404, 'File tidak ditemukan');
        }

        // Ambil URL file dari Supabase
        $fileUrl = $this->supabase->getFileUrl($letter->letter_path);

        // Gunakan file_get_contents untuk mendownload isi file
        $fileContents = @file_get_contents($fileUrl);
        if ($fileContents === false) {
            abort(404, 'File tidak ditemukan di Supabase');
        }

        // Tentukan nama file
        $fileName = basename($letter->letter_path);

        // Paksa download dengan header yang benar
        return response($fileContents, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $letter = Letter::findOrFail($id);
        $filePath = $letter->letter_path;
    
        // Hapus file lama jika ada
        // if ($filePath && Storage::disk('public')->exists($filePath)) {
        //     Storage::disk('public')->delete($filePath);
        // }
    
        $letter->delete();
    
        return redirect()->route('letter.index')->with('success', 'Surat berhasil dihapus');
    }
}
