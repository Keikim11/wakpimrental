<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Pengembalian;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengembalians = Pengembalian::with(['penyewaan.mobil', 'penyewaan.pelanggan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pengembalians.index', compact('pengembalians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($penyewaan_id = null)
    {
        if ($penyewaan_id) {
            $penyewaan = Penyewaan::with(['mobil', 'pelanggan'])->findOrFail($penyewaan_id);
            
            if ($penyewaan->status != 'aktif') {
                return redirect()->route('penyewaans.index')
                    ->with('error', 'Hanya penyewaan aktif yang dapat dikembalikan.');
            }
            
            return view('pengembalians.create', compact('penyewaan'));
        }

        // Jika tidak ada penyewaan_id, tampilkan daftar penyewaan aktif
        $penyewaansAktif = Penyewaan::with(['mobil', 'pelanggan'])
            ->where('status', 'aktif')
            ->get();
            
        return view('pengembalians.select_penyewaan', compact('penyewaansAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_mobil' => 'required|in:baik,rusak_ringan,rusak_berat',
            'denda' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::findOrFail($request->penyewaan_id);

            // Validasi status penyewaan
            if ($penyewaan->status != 'aktif') {
                return redirect()->back()
                    ->with('error', 'Penyewaan sudah tidak aktif.')
                    ->withInput();
            }

            // Buat pengembalian
            $totalPembayaran = $penyewaan->total_biaya + $request->denda;

$pengembalian = Pengembalian::create([
    'penyewaan_id' => $request->penyewaan_id,
    'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
    'kondisi_mobil' => $request->kondisi_mobil,
    'denda' => $request->denda,
     'total_pembayaran' => $request->total_pembayaran ?? ($penyewaan->total_biaya + $request->denda),
    'catatan' => $request->catatan,
]);


            // Update status penyewaan
            $penyewaan->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'denda' => $request->denda
            ]);

            // Update status mobil menjadi tersedia
            $mobil = Mobil::find($penyewaan->mobil_id);
            $mobil->update(['status' => 'tersedia']);

            DB::commit();

            return redirect()->route('pengembalians.show', $pengembalian->id)
                ->with('success', 'Pengembalian berhasil dicatat.');

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
    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load(['penyewaan.mobil', 'penyewaan.pelanggan']);
        return view('pengembalians.show', compact('pengembalian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengembalian $pengembalian)
    {
        $pengembalian->load(['penyewaan.mobil', 'penyewaan.pelanggan']);
        return view('pengembalians.edit', compact('pengembalian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengembalian $pengembalian)
    {
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_mobil' => 'required|in:baik,rusak_ringan,rusak_berat',
            'denda' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pengembalian->update([
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'kondisi_mobil' => $request->kondisi_mobil,
                'denda' => $request->denda,
                'catatan' => $request->catatan,
            ]);

            // Update juga di tabel penyewaan
            $penyewaan = $pengembalian->penyewaan;
            $penyewaan->update([
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'denda' => $request->denda
            ]);

            DB::commit();

            return redirect()->route('pengembalians.show', $pengembalian->id)
                ->with('success', 'Data pengembalian berhasil diperbarui.');

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
    public function destroy(Pengembalian $pengembalian)
    {
        try {
            DB::beginTransaction();

            // Kembalikan status penyewaan ke aktif
            $penyewaan = $pengembalian->penyewaan;
            $penyewaan->update([
                'status' => 'aktif',
                'tanggal_kembali_aktual' => null,
                'denda' => 0
            ]);

            // Update status mobil ke disewa
            $mobil = Mobil::find($penyewaan->mobil_id);
            $mobil->update(['status' => 'disewa']);

            // Hapus pengembalian
            $pengembalian->delete();

            DB::commit();

            return redirect()->route('pengembalians.index')
                ->with('success', 'Pengembalian berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengembalians.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hitung denda otomatis
     */
    public function hitungDenda(Request $request, $id)
{
    $penyewaan = Penyewaan::with('mobil')->findOrFail($id);

   $request->validate([
    'tanggal_kembali_aktual' => 'required',
    'kondisi_mobil' => 'required|in:baik,rusak_ringan,rusak_berat'
]);


    if (!$penyewaan->mobil) {
        return response()->json([
            'success' => false,
            'message' => 'Mobil tidak ditemukan'
        ], 404);
    }

    $tanggalKembaliAktual = Carbon::parse($request->tanggal_kembali_aktual);
    $tanggalKembaliRencana = Carbon::parse($penyewaan->tanggal_kembali_rencana);

    $hariTerlambat = 0;
    $dendaKeterlambatan = 0;

    if ($tanggalKembaliAktual->greaterThan($tanggalKembaliRencana)) {
        $hariTerlambat = $tanggalKembaliAktual->diffInDays($tanggalKembaliRencana);
        $tarifSewaPerHari = $penyewaan->mobil->tarif_sewa_per_hari;
        $dendaKeterlambatan = $hariTerlambat * ($tarifSewaPerHari * 0.1);
    }

    $dendaKerusakan = match ($request->kondisi_mobil) {
        'rusak_ringan' => 500000,
        'rusak_berat'  => 2000000,
        default        => 0
    };

    $totalDenda = $dendaKeterlambatan + $dendaKerusakan;

    return response()->json([
        'success' => true,
        'hari_terlambat' => $hariTerlambat,
        'denda_keterlambatan' => $dendaKeterlambatan,
        'denda_kerusakan' => $dendaKerusakan,
        'total_denda' => $totalDenda,
        'tarif_sewa_per_hari' => $penyewaan->mobil->tarif_sewa_per_hari,
        'tanggal_kembali_rencana' => $tanggalKembaliRencana->format('d/m/Y'),
        'tanggal_kembali_aktual' => $tanggalKembaliAktual->format('d/m/Y')
    ]);
}

    /**
     * Generate laporan pengembalian
     */
    public function laporan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $pengembalians = Pengembalian::with(['penyewaan.mobil', 'penyewaan.pelanggan'])
            ->whereBetween('tanggal_kembali_aktual', [$startDate, $endDate])
            ->orderBy('tanggal_kembali_aktual', 'desc')
            ->get();

        $totalDenda = $pengembalians->sum('denda');

        return view('pengembalians.laporan', compact('pengembalians', 'startDate', 'endDate', 'totalDenda'));
    }
}