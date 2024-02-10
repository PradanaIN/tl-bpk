<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('livewire.kelola-pengguna.index', [
            'title' => 'Kelola Pengguna',
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unit_kerja = UnitKerja::all();

        return view('livewire.kelola-pengguna.create', [
            'title' => 'Tambah Pengguna',
            'unit_kerja' => $unit_kerja,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'unit_kerja' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        return redirect('/kelola-pengguna')->with('create', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('livewire.kelola-pengguna.edit', [
            'title' => 'Edit Pengguna',
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'unit_kerja' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::where('id', $user->id)
            ->update($validatedData);

        return redirect('/kelola-pengguna')->with('update', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        User::destroy($user->id);

        return redirect('/kelola-pengguna')->with('delete', 'Data berhasil dihapus!');
    }
}
