<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Mobil;
use App\Models\Pelanggan;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Carbon\Carbon;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('penyewaans.index', compact('penyewaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($mobil_id = null)
    {
        $mobils = Mobil::where('status', 'tersedia')->get();
        $pelanggans = Pelanggan::all();
        $selectedMobil = $mobil_id ? Mobil::find($mobil_id) : null;
        
        return view('penyewaans.create', compact('mobils', 'pelanggans', 'selectedMobil'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'mobil_id' => 'required|exists:mobils,id',
            'tanggal_sewa' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_sewa',
            'durasi_sewa' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Cek ketersediaan mobil
            $mobil = Mobil::findOrFail($request->mobil_id);
            if ($mobil->status != 'tersedia') {
                return redirect()->back()
                    ->with('error', 'Mobil tidak tersedia untuk disewa.')
                    ->withInput();
            }

            // Hitung total biaya
            $totalBiaya = $mobil->tarif_sewa_per_hari * $request->durasi_sewa;

            // Buat penyewaan
            $penyewaan = Penyewaan::create([
                'pelanggan_id' => $request->pelanggan_id,
                'mobil_id' => $request->mobil_id,
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'durasi_sewa' => $request->durasi_sewa,
                'total_biaya' => $totalBiaya,
                'status' => 'aktif',
            ]);

            // Update status mobil
            $mobil->update(['status' => 'disewa']);

            DB::commit();

            return redirect()->route('penyewaans.show', $penyewaan->id)
                ->with('success', 'Penyewaan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyewaan $penyewaan)
    {
        $penyewaan->load(['mobil', 'pelanggan', 'pengembalian']);
        return view('penyewaans.show', compact('penyewaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyewaan $penyewaan)
    {
        if ($penyewaan->status != 'aktif') {
            return redirect()->route('penyewaans.index')
                ->with('error', 'Hanya penyewaan aktif yang dapat diedit.');
        }

        $mobils = Mobil::where('status', 'tersedia')
            ->orWhere('id', $penyewaan->mobil_id)
            ->get();
        $pelanggans = Pelanggan::all();
        
        return view('penyewaans.edit', compact('penyewaan', 'mobils', 'pelanggans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyewaan $penyewaan)
    {
        if ($penyewaan->status != 'aktif') {
            return redirect()->route('penyewaans.index')
                ->with('error', 'Hanya penyewaan aktif yang dapat diedit.');
        }

        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'mobil_id' => 'required|exists:mobils,id',
            'tanggal_sewa' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_sewa',
            'durasi_sewa' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Jika mobil diubah, kembalikan mobil lama dan update mobil baru
            if ($penyewaan->mobil_id != $request->mobil_id) {
                $mobilLama = Mobil::find($penyewaan->mobil_id);
                $mobilLama->update(['status' => 'tersedia']);

                $mobilBaru = Mobil::find($request->mobil_id);
                if ($mobilBaru->status != 'tersedia') {
                    return redirect()->back()
                        ->with('error', 'Mobil baru tidak tersedia.')
                        ->withInput();
                }
                $mobilBaru->update(['status' => 'disewa']);
            }

            // Hitung total biaya baru
            $mobil = Mobil::find($request->mobil_id);
            $totalBiaya = $mobil->tarif_sewa_per_hari * $request->durasi_sewa;

            // Update penyewaan
            $penyewaan->update([
                'pelanggan_id' => $request->pelanggan_id,
                'mobil_id' => $request->mobil_id,
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'durasi_sewa' => $request->durasi_sewa,
                'total_biaya' => $totalBiaya,
            ]);

            DB::commit();

            return redirect()->route('penyewaans.show', $penyewaan->id)
                ->with('success', 'Penyewaan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyewaan $penyewaan)
    {
        try {
            DB::beginTransaction();

            if ($penyewaan->status == 'aktif') {
                // Kembalikan status mobil
                $mobil = Mobil::find($penyewaan->mobil_id);
                $mobil->update(['status' => 'tersedia']);
            }

            $penyewaan->delete();

            DB::commit();

            return redirect()->route('penyewaans.index')
                ->with('success', 'Penyewaan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('penyewaans.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Selesaikan penyewaan
     */
    public function selesai($id)
    {
        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::findOrFail($id);
            
            if ($penyewaan->status != 'aktif') {
                return redirect()->back()
                    ->with('error', 'Hanya penyewaan aktif yang dapat diselesaikan.');
            }

            // Update status penyewaan
            $penyewaan->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => now()
            ]);

            // Kembalikan status mobil
            $mobil = Mobil::find($penyewaan->mobil_id);
            $mobil->update(['status' => 'tersedia']);

            DB::commit();

            return redirect()->route('penyewaans.show', $penyewaan->id)
                ->with('success', 'Penyewaan berhasil diselesaikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan penyewaan
     */
    public function batal($id)
    {
        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::findOrFail($id);
            
            if ($penyewaan->status != 'aktif') {
                return redirect()->back()
                    ->with('error', 'Hanya penyewaan aktif yang dapat dibatalkan.');
            }

            // Update status penyewaan
            $penyewaan->update([
                'status' => 'batal'
            ]);

            // Kembalikan status mobil
            $mobil = Mobil::find($penyewaan->mobil_id);
            $mobil->update(['status' => 'tersedia']);

            DB::commit();

            return redirect()->route('penyewaans.show', $penyewaan->id)
                ->with('success', 'Penyewaan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate invoice
     */
    public function invoice($id)
    {
        $penyewaan = Penyewaan::with(['mobil', 'pelanggan'])->findOrFail($id);
        
        return view('penyewaans.invoice', compact('penyewaan'));
    }

    /**
     * Get tarif mobil untuk AJAX
     */
    public function getTarifMobil($id)
    {
        $mobil = Mobil::find($id);
        
        if ($mobil) {
            return response()->json([
                'success' => true,
                'tarif' => $mobil->tarif_sewa_per_hari,
                'status' => $mobil->status
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mobil tidak ditemukan'
        ]);
    }

    /**
     * Get penyewaan aktif untuk AJAX
     */
    public function getPenyewaanAktif()
    {
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan'])
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($penyewaans);
    }

    /**
     * Calculate total biaya
     */
    public function calculateTotal(Request $request)
    {
        $request->validate([
            'mobil_id' => 'required|exists:mobils,id',
            'durasi_sewa' => 'required|integer|min:1',
        ]);

        $mobil = Mobil::find($request->mobil_id);
        $totalBiaya = $mobil->tarif_sewa_per_hari * $request->durasi_sewa;

        return response()->json([
            'success' => true,
            'total_biaya' => $totalBiaya,
            'tarif_per_hari' => $mobil->tarif_sewa_per_hari
        ]);
    }
}