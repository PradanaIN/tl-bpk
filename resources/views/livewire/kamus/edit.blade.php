@extends('layouts.horizontal')

@section('section')

<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="row">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-vertical" action="/kelola-kamus/{{ $kamus->id }}" method="post">
                            @method('put')
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="first-name-vertical">Nama</label>
                                                    <input type="text" id="first-name-vertical" class="form-control"
                                                        name="nama" placeholder="Nama" value="{{ old('nama', $kamus->nama) }}"">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="contact-info-vertical">Jenis Kamus</label>
                                                    <select class="form-select" id="basicSelect" name="jenis" value="{{ old('jenis', $kamus->jenis) }}">
                                                        <option value="Pemeriksaan">Pemeriksaan</option>
                                                        <option value="Temuan">Temuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                    <div class="col-12 d-flex justify-between justify-content-end mt-3">
                                        <button type="reset" class="btn btn-light-secondary me-3 mb-1">
                                            <a href="/kelola-pengguna" style="text-decoration: none; color: black;">Batal</a>
                                        </button>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
