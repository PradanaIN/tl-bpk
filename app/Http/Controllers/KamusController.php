<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use Illuminate\Http\Request;

class KamusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('livewire.kamus.index', [
            'kamus' => Kamus::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('livewire.kamus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
        ]);

        Kamus::create($validatedData);

        return redirect('/kelola-kamus');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kamus $kamus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kamus $kamus)
    {
        return view('livewire.kamus.edit', [
            'kamus' => $kamus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kamus $kamus)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
        ]);

        $kamus->update($validatedData);

        return redirect('/kelola-kamus');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kamus $kamus)
    {
        Kamus::destroy($kamus->id);

        return redirect('/kelola-kamus');
    }
}
