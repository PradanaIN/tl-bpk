@extends('layouts.horizontal')

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
                                                <label for="first-name-vertical">Pemeriksaan</label>
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                    name="pemeriksaan" placeholder="Pemeriksaan">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email-id-vertical">Jenis Pemeriksaan</label>
                                                <select class="form-select" id="basicSelect" name="jenis_pemeriksaan">
                                                    <option value="Laporan Keuangan">Laporan Keuangan</option>
                                                    <option value="Kinerja">Kinerja</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-info-vertical">Tahun Pemeriksaan</label>
                                                <select class="form-select" id="basicSelect" name="tahun_pemeriksaan">
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
                                                <label for="password-vertical">Hasil Pemeriksaan</label>
                                                <textarea class="form-control" id="contact-info-vertical" rows="3"
                                                    name="hasil_pemeriksaan" placeholder="Hasil Pemeriksaan"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="card-title mt-3">B. Detail Rekomendasi</h4>
                                    <div class="mt-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email-id-vertical">Jenis Temuan</label>
                                                <select class="form-select" id="basicSelect" name="jenis_temuan">
                                                    <option value="Belanja">Belanja</option>
                                                    <option value="SPI">SPI</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-info-vertical">Uraian Temuan</label>
                                                <textarea class="form-control" id="contact-info-vertical" rows="3"
                                                    name="uraian_temuan" placeholder="Uraian Temuan"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="password-vertical">Rekomendasi</label>
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                name="rekomendasi" placeholder="Rekomendasi">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group
                                                <label for="password-vertical">Catatan Rekomendasi</label>
                                                <textarea class="form-control" id="contact-info-vertical" rows="3"
                                                    name="catatan_rekomendasi" placeholder="Catatan Rekomendasi"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="card-title mt-3">C. Tindak Lanjut</h4>
                                    <div class="col-12 mt-3">
                                        <div class="repeater-default">
                                            <div data-repeater-list="group-a">
                                                <div data-repeater-item>
                                                    <div class="row d-flex
                                                        justify-content-between">
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="first-name-vertical">Tindak Lanjut</label>
                                                                <input type="text" id="first-name-vertical" class="form-control"
                                                                    name="tindak_lanjut[]" placeholder="Tindak lanjut">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="contact-info-vertical">Unit Kerja</label>
                                                                <select class="form-select" id="basicSelect" name="unit_kerja[]">
                                                                    <option value="">Pilih PIC Unit Kerja</option>
                                                                    @foreach ($unit_kerja as $unit_kerja)
                                                                        <option value="{{ $unit_kerja->nama }}">{{ $unit_kerja->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="contact-info-vertical">Tim Pemantauan</label>
                                                                <select class="form-select" id="basicSelect" name="tim_pemantauan[]">
                                                                    <option value="">Pilih PIC Tim Pemantauan</option>
                                                                    <option value="Tim Pemantauan Wilayah I">Tim Pemantauan Wilayah I</option>
                                                                    <option value="Tim Pemantauan Wilayah II">Tim Pemantauan Wilayah II</option>
                                                                    <option value="Tim Pemantauan Wilayah III">Tim Pemantauan Wilayah II</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="contact-info-vertical">Tenggat Waktu</label>
                                                                <input type="date" id="contact-info-vertical" class="form-control"
                                                                    name="tenggat_waktu[]" placeholder="Tenggat Waktu">
                                                            </div>
                                                        </div>
                                                        <div class="col-auto d-flex ms-auto">
                                                            <div class="col-auto">
                                                            <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                                                <i data-feather="plus" class="me-25"></i>
                                                            </button>
                                                            <button class="btn btn-icon btn-danger" type="button" id="btnDelete" data-repeater-delete>
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

        // Inisialisasi ulang select
        var select = newItem.querySelectorAll('select');
        select.forEach(function(item) {
            new Choices(item);
        });
    });

    // Event listener untuk tombol Hapus
    repeaterDefault.addEventListener('click', function (e) {
        if (e.target && e.target.matches('[data-repeater-delete]')) {
            e.target.closest('[data-repeater-item]').remove();
        }
    });

    // Inisialisasi select
    var select = repeaterDefault.querySelectorAll('select');
    select.forEach(function(item) {
        new Choices(item);
    });
});

// document.getElementById('btnDelete').addEventListener('click', function () {
//     if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
//         document.getElementById('deleteForm').submit();
//     }
// });

</script>

@endsection
