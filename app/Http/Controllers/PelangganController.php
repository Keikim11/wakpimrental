<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggans = Pelanggan::orderBy('created_at', 'desc')->get();
        return view('pelanggans.index', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|unique:pelanggans,no_ktp|max:16',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
            'no_ktp.max' => 'Nomor KTP maksimal 16 digit.',
        ]);

        try {
            DB::beginTransaction();

            Pelanggan::create($request->all());

            DB::commit();

            return redirect()->route('pelanggans.index')
                ->with('success', 'Data pelanggan berhasil ditambahkan.');
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
    public function show($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $riwayatSewa = Penyewaan::with('mobil')
            ->where('pelanggan_id', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pelanggans.show', compact('pelanggan', 'riwayatSewa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggans.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email,' . $pelanggan->id,
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|unique:pelanggans,no_ktp,' . $pelanggan->id . '|max:16',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
            'no_ktp.max' => 'Nomor KTP maksimal 16 digit.',
        ]);

        try {
            DB::beginTransaction();

            $pelanggan->update($request->all());

            DB::commit();

            return redirect()->route('pelanggans.index')
                ->with('success', 'Data pelanggan berhasil diperbarui.');
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
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        try {
            DB::beginTransaction();

            // Cek apakah pelanggan memiliki riwayat penyewaan
            $penyewaanCount = Penyewaan::where('pelanggan_id', $pelanggan->id)->count();
            
            if ($penyewaanCount > 0) {
                return redirect()->route('pelanggans.index')
                    ->with('error', 'Tidak dapat menghapus pelanggan karena memiliki riwayat penyewaan.');
            }

            $pelanggan->delete();

            DB::commit();

            return redirect()->route('pelanggans.index')
                ->with('success', 'Data pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pelanggans.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan riwayat penyewaan pelanggan
     */
    public function riwayat($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $riwayatSewa = Penyewaan::with(['mobil', 'pengembalian'])
            ->where('pelanggan_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelanggans.riwayat', compact('pelanggan', 'riwayatSewa'));
    }

    /**
     * Cek data pelanggan berdasarkan email (untuk AJAX)
     */
    public function cekPelanggan($email)
    {
        $pelanggan = Pelanggan::where('email', $email)->first();
        
        if ($pelanggan) {
            return response()->json([
                'exists' => true,
                'pelanggan' => $pelanggan
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }

    /**
     * Search pelanggan (untuk AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $pelanggans = Pelanggan::where('nama', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('no_ktp', 'like', "%{$search}%")
            ->get();

        return response()->json($pelanggans);
    }
}