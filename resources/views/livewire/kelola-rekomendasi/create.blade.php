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
                        <form class="form form-vertical" action="/kelola-rekomendasi" method="post">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <h4 class="card-title">A. Detail Pemeriksaan</h4>
                                    <div class="mt-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="pemeriksaan">Pemeriksaan</label>
                                                <input type="text" id="pemeriksaan" class="form-control"
                                                    name="pemeriksaan" placeholder="Pemeriksaan" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                                <select class="form-select" id="jenis_pemeriksaan" name="jenis_pemeriksaan" required>
                                                    <option value="">Pilih Jenis Pemeriksaan</option>
                                                    @foreach ($kamus_pemeriksaan as $kamus)
                                                        <option value="{{ $kamus->nama }}">{{ $kamus->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tahun_pemeriksaan">Tahun Pemeriksaan</label>
                                                <select class="form-select" id="tahun_pemeriksaan" name="tahun_pemeriksaan" required>
                                                    <option value="">Pilih Tahun Pemeriksaan</option>
                                                    <option value="2021">2021</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2018">2018</option>
                                                    <option value="2017">2017</option>
                                                    <option value="2016">2016</option>
                                                    <option value="2015">2015</option>
                                                    <option value="2014">2014</option>
                                                    <option value="2013">2013</option>
                                                    <option value="2012">2012</option>
                                                    <option value="2011">2011</option>
                                                    <option value="2010">2010</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="hasil_pemeriksaan">Hasil Pemeriksaan</label>
                                                <textarea class="form-control" id="hasil_pemeriksaan" rows="3"
                                                    name="hasil_pemeriksaan" placeholder="Hasil Pemeriksaan" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="card-title mt-3">B. Detail Rekomendasi</h4>
                                    <div class="mt-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="jenis_temuan">Jenis Temuan</label>
                                                <select class="form-select" id="jenis_temuan" name="jenis_temuan" required>
                                                    <option value="">Pilih Jenis Temuan</option>
                                                    @foreach ($kamus_temuan as $kamus)
                                                        <option value="{{ $kamus->nama }}">{{ $kamus->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="uraian_temuan">Uraian Temuan</label>
                                                <textarea class="form-control" id="uraian_temuan" rows="3"
                                                    name="uraian_temuan" placeholder="Uraian Temuan" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="rekomendasi">Rekomendasi</label>
                                                <input type="text" id="rekomendasi" class="form-control"
                                                name="rekomendasi" placeholder="Rekomendasi" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="catatan_rekomendasi">Catatan Rekomendasi</label>
                                                <textarea class="form-control" id="catatan_rekomendasi" rows="3"
                                                    name="catatan_rekomendasi" placeholder="Catatan Rekomendasi" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="card-title mt-3">C. Tindak Lanjut</h4>
                                    <div class="col-12 mt-3">
                                        <div class="repeater-default">
                                            <div class="col-auto d-flex justify-content-end ms-auto">
                                                <div class="col-auto">
                                                    <div data-repeater-create>
                                                        <button class="btn btn-primary" type="button" id="btnTambah" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Form">
                                                            <i data-feather="plus" class="me-25"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-repeater-list="group-a">
                                                <div data-repeater-item>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="tindak_lanjut">Tindak Lanjut</label>
                                                                <input type="text" id="tindak_lanjut" class="form-control"
                                                                    name="tindak_lanjut[]" placeholder="Tindak lanjut">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="unit_kerja">Unit Kerja</label>
                                                                <select class="form-select" id="unit_kerja" name="unit_kerja[]">
                                                                    <option value="">Pilih PIC Unit Kerja</option>
                                                                    @foreach ($unit_kerja as $unit_kerja)
                                                                        <option value="{{ $unit_kerja->nama }}">{{ $unit_kerja->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="tim_pemantauan">Tim Pemantauan</label>
                                                                <select class="form-select" id="tim_pemantauan" name="tim_pemantauan[]">
                                                                    <option value="">Pilih PIC Tim Pemantauan</option>
                                                                    <option value="Tim Pemantauan Wilayah I">Tim Pemantauan Wilayah I</option>
                                                                    <option value="Tim Pemantauan Wilayah II">Tim Pemantauan Wilayah II</option>
                                                                    <option value="Tim Pemantauan Wilayah III">Tim Pemantauan Wilayah II</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="tenggat_waktu">Tenggat Waktu</label>
                                                                <input type="date" id="tenggat_waktu" class="form-control"
                                                                    name="tenggat_waktu[]" placeholder="Tenggat Waktu">
                                                            </div>
                                                        </div>
                                                        <div class="col-auto d-flex ms-auto">
                                                            <div class="col-auto">
                                                            <button class="btn btn-icon btn-danger" type="button" id="btnDelete" data-repeater-delete data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Form">
                                                                <i data-feather="trash-2" class="me-25"></i>
                                                            </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-between justify-content-end mt-5">
                                        <button type="reset" class="btn btn-light-secondary me-3 mb-1">Batal</button>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Tambah</button>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

// select2
$(document).ready(function() {
    $('.form-select').select2({
        theme: 'bootstrap-5',
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var repeaterDefault = document.querySelector('.repeater-default');
    var btnTambah = repeaterDefault.querySelector('[data-repeater-create]');

    // Event listener untuk tombol Tambah
    btnTambah.addEventListener('click', function () {
        var repeaterList = repeaterDefault.querySelector('[data-repeater-list="group-a"]');
        var newItem = repeaterList.querySelector('[data-repeater-item]').cloneNode(true);

        // Bersihkan nilai input pada item baru
        var inputs = newItem.querySelectorAll('input, select');
        inputs.forEach(function(input) {
            input.value = '';
        });

        repeaterList.appendChild(newItem);


        $(document).ready(function() {
            $('.form-select').removeClass('select2-hidden-accessible');
            $('.form-select').next().remove();
            $('.form-select').select2();
        });
});

    // Event listener untuk tombol Hapus
    repeaterDefault.addEventListener('click', function (e) {
        if (e.target && e.target.matches('[data-repeater-delete]')) {
            e.target.closest('[data-repeater-item]').remove();
        }
    });
});

</script>

<script>
    // warning batal button
    document.querySelector('.btn-light-secondary').addEventListener('click', function(e) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang diinputkan tidak akan disimpan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Tidak, tetap disini'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = '/kelola-rekomendasi';
            }
        })
    });
</script>

@endsection
