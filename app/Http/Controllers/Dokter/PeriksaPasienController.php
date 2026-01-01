<?php 

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller{
    public function index(){
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function($query) use ($dokterId){
                $query->where('id_dokter', $dokterId); 
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id){
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request){
        $request->validate([
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        // Decode JSON data
        $obatData = json_decode($request->obat_json, true);

        // ✅ CEK FORMAT DATA
        if (!is_array($obatData) || empty($obatData)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Data obat tidak valid']);
        }

        // ✅ VALIDASI STOK - CEK SEMUA OBAT DULU
        foreach ($obatData as $item) {
            // Pastikan format data benar
            if (!isset($item['id_obat']) || !isset($item['jumlah'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Format data obat tidak valid']);
            }

            $idObat = $item['id_obat'];
            $jumlah = $item['jumlah'];

            $obat = Obat::find($idObat);
            
            if (!$obat) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => "Obat dengan ID {$idObat} tidak ditemukan"]);
            }

            if ($obat->stok < $jumlah) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => "Stok obat {$obat->nama_obat} tidak mencukupi. Stok tersedia: {$obat->stok}, diminta: {$jumlah}"]);
            }
        }

        // ✅ GUNAKAN TRANSACTION UNTUK KEAMANAN DATA
        DB::beginTransaction();
        
        try {
            // Simpan data periksa
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $request->biaya_periksa + 150000,
            ]);

            // Simpan detail periksa dan kurangi stok
            foreach ($obatData as $item) {
                $idObat = (int) $item['id_obat'];
                $jumlah = (int) $item['jumlah'];

                // Simpan detail periksa dengan jumlah
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $idObat,
                    'jumlah' => $jumlah,
                ]);

                // ✅ KURANGI STOK OBAT
                $obat = Obat::find($idObat);
                $obat->stok -= $jumlah;
                $obat->save();
            }

            DB::commit();

            return redirect()->route('periksa-pasien.index')
                ->with('success', 'Data periksa berhasil disimpan dan stok obat telah diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}