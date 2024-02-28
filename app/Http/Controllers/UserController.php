<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
        $role = Role::all();

        return view('livewire.kelola-pengguna.create', [
            'title' => 'Tambah Pengguna',
            'unit_kerja' => $unit_kerja,
            'role' => $role,
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

        // assign role
        $user = User::where('email', $validatedData['email'])->first();
        $user->assignRole($validatedData['role']);

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
        $unit_kerja = UnitKerja::all();
        $role = Role::all();

        return view('livewire.kelola-pengguna.edit', [
            'title' => 'Edit Pengguna',
            'user' => $user,
            'unit_kerja' => $unit_kerja,
            'role' => $role,
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

        if (substr($validatedData['password'], 0, 4) != '$2y$') {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        User::where('id', $user->id)
            ->update($validatedData);

        // update assign role
        $user = User::where('id', $user->id)->first();
        $user->syncRoles($validatedData['role']);

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
