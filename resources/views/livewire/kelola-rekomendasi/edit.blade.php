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
                        <form class="form form-vertical" action="/kelola-rekomendasi/{{ $rekomendasi->id }}" method="post">
                            @method('put')
                            @csrf
                            <input type="hidden" name="status_rekomendasi" value="{{ $rekomendasi->status_rekomendasi }}">
                            <div class="form-body">
                                <div class="row">
                                    <h4 class="card-title">A. Detail Pemeriksaan</h4>
                                    <div class="mt-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="pemeriksaan">Pemeriksaan</label>
                                                <input type="text" id="pemeriksaan" class="form-control"
                                                    name="pemeriksaan" placeholder="Pemeriksaan" value="{{ old('pemeriksaan', $rekomendasi->pemeriksaan) }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tahun_pemeriksaan">Tahun Pemeriksaan</label>
                                                <input type="number" id="tahun_pemeriksaan" class="form-control"
                                                    name="tahun_pemeriksaan" placeholder="Tahun Pemeriksaan" value="{{ old('tahun_pemeriksaan', $rekomendasi->tahun_pemeriksaan) }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                                <select class="form-select" id="jenis_pemeriksaan" name="jenis_pemeriksaan" value="{{ old('jenis_pemeriksaan', $rekomendasi->jenis_pemeriksaan) }}">
                                                    <option value="Laporan Keuangan">Laporan Keuangan</option>
                                                    <option value="Kinerja">Kinerja</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="hasil_pemeriksaan">Hasil Pemeriksaan</label>
                                                <textarea class="form-control" id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="3" placeholder="Hasil Pemeriksaan">{{ old('hasil_pemeriksaan', $rekomendasi->hasil_pemeriksaan) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="card-title mt-3">B. Detail Rekomendasi</h4>
                                    <div class="mt-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="jenis_temuan">Jenis Temuan</label>
                                                <select class="form-select" id="jenis_temuan" name="jenis_temuan" value="{{ old('jenis_temuan', $rekomendasi->jenis_temuan) }}">
                                                    <option value="Belanja">Belanja</option>
                                                    <option value="SPI">SPI</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="uraian_temuan">Uraian Temuan</label>
                                                <textarea class="form-control" id="uraian_temuan" name="uraian_temuan" rows="3" placeholder="Uraian Temuan">{{ old('uraian_temuan', $rekomendasi->uraian_temuan) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="rekomendasi">Rekomendasi</label>
                                                <input type="text" id="rekomendasi" class="form-control"
                                                name="rekomendasi" placeholder="Rekomendasi" value="{{ old('rekomendasi', $rekomendasi->rekomendasi) }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="catatan_rekomendasi">Catatan Rekomendasi</label>
                                                <textarea class="form-control" id="catatan_rekomendasi" name="catatan_rekomendasi" rows="3" placeholder="Catatan Rekomendasi">{{ old('catatan_rekomendasi', $rekomendasi->catatan_rekomendasi) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="card-title mt-3">C. Tindak Lanjut</h4>
                                    <div class="mt-2">
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
                                                @foreach($rekomendasi->tindakLanjut as $index => $tindakLanjut)
                                                <div data-repeater-item>
                                                    <div class="row d-flex justify-content-between">
                                                        <input type="hidden" name="id[]" value="{{ $tindakLanjut->id }}">
                                                        <input type="hidden" name="status_tindak_lanjut[]" value="{{ $tindakLanjut->status_tindak_lanjut }}">
                                                        <input type="hidden" name="dokumen_tindak_lanjut[]" value="{{ $tindakLanjut->dokumen_tindak_lanjut }}">
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
                                                                    <option value="Tim Pemantauan Wilayah III">Tim Pemantauan Wilayah III</option>
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
                                                            <button class="btn btn-icon btn-danger" type="button" id="btnDelete" data-repeater-delete data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Form">
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



@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
</script>

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
