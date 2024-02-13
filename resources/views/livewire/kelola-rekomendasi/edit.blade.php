@extends('layouts.vertical')


@section('section')

<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="row">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-vertical" action="/kelola-rekomendasi/{{ $rekomendasi->id }}" method="post">
                            @method('put')
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <h4 class="card-title">A. Detail Pemeriksaan</h4>
                                    <div class="mt-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Pemeriksaan</label>
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                    name="pemeriksaan" placeholder="Pemeriksaan" value="{{ old('pemeriksaan', $rekomendasi->pemeriksaan) }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email-id-vertical">Jenis Pemeriksaan</label>
                                                <select class="form-select" id="basicSelect" name="jenis_pemeriksaan" value="{{ old('jenis_pemeriksaan', $rekomendasi->jenis_pemeriksaan) }}">
                                                    <option value="Laporan Keuangan">Laporan Keuangan</option>
                                                    <option value="Kinerja">Kinerja</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-info-vertical">Tahun Pemeriksaan</label>
                                                <select class="form-select" id="basicSelect" name="tahun_pemeriksaan" value="{{ old('tahun_pemeriksaan', $rekomendasi->tahun_pemeriksaan) }}">
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
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                name="hasil_pemeriksaan" placeholder="Hasil Pemeriksaan" value="{{ old('hasil_pemeriksaan', $rekomendasi->hasil_pemeriksaan) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="card-title mt-3">B. Detail Rekomendasi</h4>
                                    <div class="mt-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email-id-vertical">Jenis Temuan</label>
                                                <select class="form-select" id="basicSelect" name="jenis_temuan" value="{{ old('jenis_temuan', $rekomendasi->jenis_temuan) }}">
                                                    <option value="Belanja">Belanja</option>
                                                    <option value="SPI">SPI</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-info-vertical">Uraian Temuan</label>
                                                <input type="text" id="contact-info-vertical" class="form-control"
                                                    name="uraian_temuan" placeholder="Uraian Temuan" value="{{ old('uraian_temuan', $rekomendasi->uraian_temuan) }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="password-vertical">Rekomendasi</label>
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                name="rekomendasi" placeholder="Rekomendasi" value="{{ old('rekomendasi', $rekomendasi->rekomendasi) }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group
                                                <label for="password-vertical">Catatan Rekomendasi</label>
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                name="catatan_rekomendasi" placeholder="Catatan Rekomendasi" value="{{ old('catatan_rekomendasi', $rekomendasi->catatan_rekomendasi) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="card-title mt-3">C. Tindak Lanjut</h4>
                                    <div class="mt-2">
                                        <div class="repeater-default">
                                            <div data-repeater-list="group-a">
                                                @foreach($rekomendasi->tindakLanjut as $index => $tindakLanjut)
                                                <div data-repeater-item>
                                                    <div class="row d-flex justify-content-between">
                                                        <input type="hidden" name="id[]" value="{{ $tindakLanjut->id }}">
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="tindak_lanjut{{$index}}">Tindak Lanjut</label>
                                                                <input type="text" id="tindak_lanjut{{$index}}" class="form-control" name="tindak_lanjut[]" value="{{ $tindakLanjut->tindak_lanjut }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="unit_kerja{{$index}}">Unit Kerja</label>
                                                                <select class="form-select" id="unit_kerja{{$index}}" name="unit_kerja[]">
                                                                    <option value="{{ $tindakLanjut->unit_kerja }}">{{ $tindakLanjut->unit_kerja }}</option>
                                                                    @foreach ($unit_kerja as $unit)
                                                                        <option value="{{ $unit->nama }}">{{ $unit->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <div class="form-group">
                                                                <label for="contact-info-vertical">Tim Pemantauan</label>
                                                                <select class="form-select" id="basicSelect" name="tim_pemantauan[]">
                                                                    <option value="{{ $tindakLanjut->tim_pemantauan }}">{{ $tindakLanjut->tim_pemantauan }}</option>
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
                                                                    name="tenggat_waktu[]" placeholder="Tenggat Waktu" value="{{ $tindakLanjut->tenggat_waktu }}">
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
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-12 d-flex justify-between justify-content-end mt-5">
                                        <button type="reset" class="btn btn-light-secondary me-3 mb-1">Batal</button>
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
