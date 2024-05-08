@extends('layouts.horizontal')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

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
                                                        name="nama" placeholder="Nama" value="{{ old('nama', $kamus->nama) }} " required>
                                                </div>
                                                @error('nama')
                                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="contact-info-vertical">Jenis Kamus</label>
                                                    <select class="form-select" id="basicSelect" name="jenis" required>
                                                        <option value="{{ old('jenis', $kamus->jenis) }}">{{ $kamus->jenis }}</option>
                                                        <option value="Pemeriksaan">Pemeriksaan</option>
                                                        <option value="Temuan">Temuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                    <div class="col-12 d-flex justify-between justify-content-end mt-5">
                                        <button type="reset" class="btn btn-light-secondary me-3 mb-1">
                                            Batal
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


@section('script')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// select2
$(document).ready(function() {
    $('.form-select').select2({
        theme: 'bootstrap-5',
    });
});
</script>


<script>
    // warning batal button
    document.querySelector('.btn-light-secondary').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data yang sudah diinputkan tidak akan tersimpan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/kelola-kamus";
            }
        })
    });
</script>

@endsection
