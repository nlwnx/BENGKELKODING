<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(){
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    public function create(){
        return view('admin.obat.create');
    }

    public function store(Request $request){
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0', // ← VALIDASI STOK
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
            'stok' => $request->stok, // ← SIMPAN STOK
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil dibuat')
            ->with('type','success');
    }

    public function edit(string $id){
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id){
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok_mode' => 'required|in:set,add,reduce',
            'stok_value' => 'required|integer|min:0',
        ]);

        $obat = Obat::findOrFail($id);
        
        // Hitung stok baru berdasarkan mode
        $stokBaru = $obat->stok;
        $mode = $request->stok_mode;
        $value = $request->stok_value;

        if ($mode === 'set') {
            $stokBaru = $value;
        } elseif ($mode === 'add') {
            $stokBaru = $obat->stok + $value;
        } elseif ($mode === 'reduce') {
            $stokBaru = max(0, $obat->stok - $value);
        }

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
            'stok' => $stokBaru,
        ]);

        $message = "Data obat berhasil diupdate. ";
        if ($mode === 'set') {
            $message .= "Stok diset menjadi {$stokBaru}.";
        } elseif ($mode === 'add') {
            $message .= "Stok ditambah {$value}, total stok: {$stokBaru}.";
        } elseif ($mode === 'reduce') {
            $message .= "Stok dikurangi {$value}, total stok: {$stokBaru}.";
        }

        return redirect()->route('obat.index')
            ->with('message', $message)
            ->with('type','success');
    }

    public function destroy(string $id){
        $obat = Obat::findOrFail($id);
        
        // Cek apakah obat masih digunakan di detail_periksa
        if ($obat->detailPeriksas()->count() > 0) {
            return redirect()->route('obat.index')
                ->with('message', 'Obat tidak dapat dihapus karena masih digunakan dalam resep')
                ->with('type','danger');
        }
        
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil dihapus')
            ->with('type','success');
    }

    // ← METHOD BARU: Update Stok Cepat (Opsional)
    public function updateStok(Request $request, string $id){
        $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'stok' => $request->stok,
        ]);

        return redirect()->route('obat.index')
            ->with('message', "Stok {$obat->nama_obat} berhasil diupdate")
            ->with('type','success');
    }
}