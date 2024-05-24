<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class KamusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master-kamus.index', [
            'title' => 'Daftar Kamus',
            'kamus' => Kamus::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-kamus.create', [
            'title' => 'Tambah Kamus',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => Str::uuid()->toString(),
                'nama' => 'required',
                'jenis' => 'required',
            ]);

            Kamus::create($validatedData);

            return redirect('/master-kamus')->with('create', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kamus $kamus)
    {
        return view('master-kamus.edit', [
            'title' => 'Edit Kamus',
            'kamus' => $kamus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kamus $kamus)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required',
                'jenis' => 'required',
            ]);

            $kamus->update($validatedData);

            return redirect('/master-kamus')->with('update', 'Data berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kamus $kamus)
    {
        try {
            Kamus::destroy($kamus->id);

            return redirect('/master-kamus')->with('delete', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
