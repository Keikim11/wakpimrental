<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'karyawan')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'role' => 'karyawan',
                'is_active' => $request->is_active ?? true,
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Data karyawan berhasil ditambahkan.');

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
    public function show(User $user)
    {
        // Hanya bisa melihat data karyawan
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat melihat data admin.');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Hanya bisa edit data karyawan
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat mengedit data admin.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Hanya bisa update data karyawan
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat mengupdate data admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'is_active' => $request->is_active ?? $user->is_active,
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Data karyawan berhasil diperbarui.');

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
    public function destroy(User $user)
    {
        // Hanya bisa hapus data karyawan
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus data admin.');
        }

        // Cek jika user sedang login
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Data karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show form for reset password
     */
    public function showResetPasswordForm(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat reset password admin.');
        }

        return view('users.reset-password', compact('user'));
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat reset password admin.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('users.show', $user->id)
                ->with('success', 'Password berhasil direset.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}