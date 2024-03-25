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
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pemeriksaan-tab" data-bs-toggle="tab" href="#pemeriksaan" role="tab"
                                aria-controls="pemeriksaan" aria-selected="true"><h6>Pemeriksaan</h6></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="rekomendasi-tab" data-bs-toggle="tab" href="#rekomendasi" role="tab"
                                aria-controls="rekomendasi" aria-selected="false"><h6>Rekomendasi</h6></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tindaklanjut-tab" data-bs-toggle="tab" href="#tindaklanjut" role="tab"
                                aria-controls="tindaklanjut" aria-selected="false"><h6>Tindak Lanjut</h6></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <form class="form form-vertical" action="/kelola-rekomendasi" method="post" data-parsley-validate>
                    @csrf
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="pemeriksaan" role="tabpanel" aria-labelledby="pemeriksaan-tab">
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="pemeriksaan">Pemeriksaan</label>
                                        <input type="text" id="pemeriksaan" class="form-control"
                                            name="pemeriksaan" placeholder="Nama Pemeriksaan" data-parsley-required="true" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="tahun_pemeriksaan">Tahun Pemeriksaan</label>
                                        <input type="number" id="tahun_pemeriksaan" class="form-control"
                                            name="tahun_pemeriksaan" placeholder="Tahun Pemeriksaan" data-parsley-required="true" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                        <select class="form-select" id="jenis_pemeriksaan" name="jenis_pemeriksaan" data-parsley-required="true" required>
                                            <option value="">Pilih Jenis Pemeriksaan</option>
                                            @foreach ($kamus_pemeriksaan as $kamus)
                                                <option value="{{ $kamus->nama }}">{{ $kamus->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="hasil_pemeriksaan">Hasil Pemeriksaan</label>
                                        <textarea class="form-control" id="hasil_pemeriksaan" rows="3"
                                        name="hasil_pemeriksaan" placeholder="Hasil Pemeriksaan" data-parsley-required="true" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="rekomendasi" role="tabpanel" aria-labelledby="rekomendasi-tab">
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="jenis_temuan">Jenis Temuan</label>
                                        <select class="form-select" id="jenis_temuan" name="jenis_temuan" data-parsley-required="true" required>
                                            <option value="">Pilih Jenis Temuan</option>
                                            @foreach ($kamus_temuan as $kamus)
                                                <option value="{{ $kamus->nama }}">{{ $kamus->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="uraian_temuan">Uraian Temuan</label>
                                        <textarea class="form-control" id="uraian_temuan" rows="3"
                                            name="uraian_temuan" placeholder="Uraian Temuan" data-parsley-required="true" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="rekomendasi">Rekomendasi</label>
                                        <textarea class="form-control" id="rekomendasi" class="form-control"
                                        name="rekomendasi" placeholder="Rekomendasi" data-parsley-required="true" required rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="catatan_rekomendasi">Catatan Rekomendasi</label>
                                        <textarea class="form-control" id="catatan_rekomendasi" rows="3"
                                            name="catatan_rekomendasi" placeholder="Catatan Rekomendasi" data-parsley-required="true" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                                <div id="formContainer">
                                    <table class="table" id="formTable">
                                        <thead>
                                            <tr>
                                                <th>Tindak Lanjut</th>
                                                <th>Unit Kerja</th>
                                                <th>Tim Pemantauan</th>
                                                <th>Tenggat Waktu</th>
                                                <th class="text-align-center">
                                                    <button type="button" class="btn btn-primary btn-tambah-row">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-between justify-content-end mt-5">
                            <button type="reset" class="btn btn-light-secondary me-3 mb-1">Batal</button>
                            <button type="submit" class="btn btn-primary me-1 mb-1">Tambah</button>
                        </div>
                    </div>
                </form>
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

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        var formTableBody = document.querySelector('#formTable tbody');
        addFormRow(formTableBody); // Tambahkan satu baris form di awal

        // Event listener untuk tombol Tambah
        document.querySelector('.btn-tambah-row').addEventListener('click', function() {
            addFormRow(formTableBody); // Tambahkan baris form ketika tombol Tambah diklik
        });

        // Event listener untuk tombol Hapus
        document.querySelectorAll('.btn-delete-row').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.closest('tr').remove(); // Hapus baris form saat tombol Hapus diklik
            });
        });

        // Inisialisasi Select2 setelah menambahkan baris
        $('.select-unit-kerja').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Unit Kerja',
        });

        $('.select-tim-pemantauan').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Tim Pemantauan'
        });
    });

    function addFormRow(tableBody) {
        var formItem = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="tindak_lanjut[]" placeholder="Tindak lanjut">
                </td>
                <td>
                    <select class="form-select select-unit-kerja" name="unit_kerja[]">
                        <option value="">Pilih PIC Unit Kerja</option>
                        @foreach ($unit_kerja as $unit)
                            <option value="{{ $unit->nama }}">{{ $unit->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-select select-tim-pemantauan" name="tim_pemantauan[]">
                        <option value="">Pilih PIC Tim Pemantauan</option>
                        <option value="Tim Pemantauan Wilayah I">Tim Pemantauan Wilayah I</option>
                        <option value="Tim Pemantauan Wilayah II">Tim Pemantauan Wilayah II</option>
                        <option value="Tim Pemantauan Wilayah III">Tim Pemantauan Wilayah III</option>
                    </select>
                </td>
                <td>
                    <input type="date" class="form-control" name="tenggat_waktu[]" placeholder="Tenggat Waktu">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-delete-row">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </td>
            </tr>`;
        tableBody.insertAdjacentHTML('beforeend', formItem);
    }
</script> --}}

<script>
    document.querySelector('.btn-tambah-row').addEventListener('click', function() {
        var formTableBody = document.querySelector('#formTable tbody');
        var formItem = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="tindak_lanjut[]" placeholder="Tindak lanjut">
                </td>
                <td>
                    <select class="form-select select-unit-kerja" name="unit_kerja[]">
                        <option value="">Pilih PIC Unit Kerja</option>
                        @foreach ($unit_kerja as $unit)
                            <option value="{{ $unit->nama }}">{{ $unit->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-select select-tim-pemantauan" name="tim_pemantauan[]">
                        <option value="">Pilih PIC Tim Pemantauan</option>
                        <option value="Tim Pemantauan Wilayah I">Tim Pemantauan Wilayah I</option>
                        <option value="Tim Pemantauan Wilayah II">Tim Pemantauan Wilayah II</option>
                        <option value="Tim Pemantauan Wilayah III">Tim Pemantauan Wilayah III</option>
                    </select>
                </td>
                <td>
                    <input type="date" class="form-control" name="tenggat_waktu[]" placeholder="Tenggat Waktu">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-delete-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
        formTableBody.insertAdjacentHTML('beforeend', formItem);

        // Inisialisasi Select2 setelah menambahkan baris
        $('.select-unit-kerja').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Unit Kerja',
        });

        $('.select-tim-pemantauan').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Tim Pemantauan'
        });

        // Event listener untuk tombol Hapus
        document.querySelectorAll('.btn-delete-row').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.closest('tr').remove();
            });
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
