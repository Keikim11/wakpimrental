<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        $mobils = Mobil::all();
        return view('mobils.index', compact('mobils'));
    }

    public function create()
    {
        return view('mobils.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'nomor_plat' => 'required|string|unique:mobils',
            'tarif_sewa_per_hari' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,disewa,maintenance',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $fotoName = time().'.'.$request->foto->extension();
            $request->foto->move(public_path('images/mobil'), $fotoName);
            $data['foto'] = $fotoName;
        }

        Mobil::create($data);

        return redirect()->route('mobils.index')
            ->with('success', 'Data mobil berhasil ditambahkan.');
    }

    public function show(Mobil $mobil)
    {
        return view('mobils.show', compact('mobil'));
    }

    public function edit(Mobil $mobil)
    {
        return view('mobils.edit', compact('mobil'));
    }

    public function update(Request $request, Mobil $mobil)
    {
        $request->validate([
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'nomor_plat' => 'required|string|unique:mobils,nomor_plat,'.$mobil->id,
            'tarif_sewa_per_hari' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,disewa,maintenance',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($mobil->foto && file_exists(public_path('images/mobil/'.$mobil->foto))) {
                unlink(public_path('images/mobil/'.$mobil->foto));
            }

            $fotoName = time().'.'.$request->foto->extension();
            $request->foto->move(public_path('images/mobil'), $fotoName);
            $data['foto'] = $fotoName;
        }

        $mobil->update($data);

        return redirect()->route('mobils.index')
            ->with('success', 'Data mobil berhasil diperbarui.');
    }

    public function destroy(Mobil $mobil)
    {
        // Hapus foto jika ada
        if ($mobil->foto && file_exists(public_path('images/mobil/'.$mobil->foto))) {
            unlink(public_path('images/mobil/'.$mobil->foto));
        }

        $mobil->delete();

        return redirect()->route('mobils.index')
            ->with('success', 'Data mobil berhasil dihapus.');
    }
}