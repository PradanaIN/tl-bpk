<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('kelola-pengguna.index', [
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

        return view('kelola-pengguna.create', [
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
        try {
            $validatedData = $request->validate([
                'nama' => 'required',
                'email' => ['required', 'email', Rule::unique('users')],
                'unit_kerja' => 'required',
                'unit_kerja_id' => 'required',
                'role' => 'required',
                'password' => 'required',
            ]);

            $validatedData['id'] = Str::uuid()->toString();
            $validatedData['password'] = bcrypt($validatedData['password']);

            User::create($validatedData);

            // assign role
            $user = User::where('email', $validatedData['email'])->first();
            $user->assignRole($validatedData['role']);

            return redirect('/kelola-pengguna')->with('create', 'Data berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // Tangkap pengecualian validasi dan teruskan pesan kesalahan ke view
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            // Tangkap pengecualian umum dan teruskan pesan kesalahan ke view
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $unit_kerja = UnitKerja::all();
        $role = Role::all();

        return view('kelola-pengguna.edit', [
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
        try {
            $validatedData = $request->validate([
                'id'=> Str::uuid()->toString(),
                'nama' => 'required',
                'email' => 'required',
                'unit_kerja' => 'required',
                'unit_kerja_id' => 'required',
                'role' => 'required',
                'password' => 'required',
            ]);

            if (substr($validatedData['password'], 0, 4) != '$2y$') {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }

            User::where('id', $user->id)->update($validatedData);

            // update assign role
            $user = User::where('id', $user->id)->first();
            $user->syncRoles($validatedData['role']);

            return redirect('/kelola-pengguna')->with('update', 'Data berhasil diubah!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withError('Gagal mengubah data pengguna.')->withInput();
        }
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
