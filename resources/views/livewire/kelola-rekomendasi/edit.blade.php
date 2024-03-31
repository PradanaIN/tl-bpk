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
                <form class="form form-vertical" action="/kelola-rekomendasi/{{ $rekomendasi->id }}" method="post" data-parsley-validate id="formTambahRekomendasi">
                    @method('put')
                    @csrf
                    <input type="hidden" name="status_rekomendasi" value="{{ $rekomendasi->status_rekomendasi }}">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="pemeriksaan" role="tabpanel" aria-labelledby="pemeriksaan-tab">
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="pemeriksaan">Pemeriksaan</label>
                                        <input type="text" id="pemeriksaan" class="form-control"
                                            name="pemeriksaan" placeholder="Nama Pemeriksaan" data-parsley-required="true" required value="{{ old('pemeriksaan', $rekomendasi->pemeriksaan) }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="tahun_pemeriksaan">Tahun Pemeriksaan</label>
                                        <input type="number" id="tahun_pemeriksaan" class="form-control"
                                            name="tahun_pemeriksaan" placeholder="Tahun Pemeriksaan" data-parsley-required="true" required value="{{ old('tahun_pemeriksaan', $rekomendasi->tahun_pemeriksaan) }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                        <select class="form-select" id="jenis_pemeriksaan" name="jenis_pemeriksaan" data-parsley-required="true" required>
                                            <option value="">Pilih Jenis Pemeriksaan</option>
                                            @foreach ($kamus_pemeriksaan as $kamus)
                                                <option value="{{ $kamus->nama }}" {{ $kamus->nama == $rekomendasi->jenis_pemeriksaan ? 'selected' : '' }}>
                                                    {{ $kamus->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="hasil_pemeriksaan">Hasil Pemeriksaan</label>
                                        <textarea class="form-control" id="hasil_pemeriksaan" rows="3"
                                        name="hasil_pemeriksaan" placeholder="Hasil Pemeriksaan" data-parsley-required="true" required>{{ old('hasil_pemeriksaan', $rekomendasi->hasil_pemeriksaan) }}</textarea>
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
                                                <option value="{{ $kamus->nama }}" {{ $kamus->nama == $rekomendasi->jenis_temuan ? 'selected' : '' }}>
                                                    {{ $kamus->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="uraian_temuan">Uraian Temuan</label>
                                        <textarea class="form-control" id="uraian_temuan" rows="3"
                                            name="uraian_temuan" placeholder="Uraian Temuan" data-parsley-required="true" required>{{ old('uraian_temuan', $rekomendasi->uraian_temuan) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="rekomendasi">Rekomendasi</label>
                                        <textarea class="form-control" id="rekomendasi" class="form-control"
                                        name="rekomendasi" placeholder="Rekomendasi" data-parsley-required="true" required rows="3">{{ old('rekomendasi', $rekomendasi->rekomendasi) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mandatory">
                                        <label class="form-label" for="catatan_rekomendasi">Catatan Rekomendasi</label>
                                        <textarea class="form-control" id="catatan_rekomendasi" rows="3"
                                            name="catatan_rekomendasi" placeholder="Catatan Rekomendasi" data-parsley-required="true" required>{{ old('catatan_rekomendasi', $rekomendasi->catatan_rekomendasi) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-warning counter" disabled>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clipboard-data me-2 mb-3"></i>
                                                <h6 class="mb-0 text-black">Jumlah Tindak Lanjut: <span id="repeaterCount">0</span></h6>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" class="btn btn-primary btn-tambah-repeater">
                                            <i class="bi bi-plus"></i>&nbsp;Tambah Tindak Lanjut
                                        </button>
                                    </div>
                                </div>
                                <div id="formContainer">
                                    @foreach($rekomendasi->tindakLanjut as $index => $tindakLanjut)
                                    <input type="hidden" name="id[]" value="{{ $tindakLanjut->id }}">
                                    <input type="hidden" name="status_tindak_lanjut[]" value="{{ $tindakLanjut->status_tindak_lanjut }}">
                                    <input type="hidden" name="dokumen_tindak_lanjut[]" value="{{ $tindakLanjut->dokumen_tindak_lanjut }}">
                                    <div class="form-repeater mb-3">
                                        <div class="form-row mb-3">
                                            <div class="col-12 form-group mandatory">
                                                <label class="form-label" for="tindak_lanjut{{$index}}">Tindak Lanjut</label>
                                                <textarea class="form-control" rows="3" name="tindak_lanjut[]" id="tindak_lanjut{{$index}}"placeholder="Tindak lanjut" data-parsley-required="true" required>{{ $tindakLanjut->tindak_lanjut }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 mb-3 form-group mandatory">
                                                <label class="form-label" for="unit_kerja{{$index}}">PIC Unit Kerja</label>
                                                <select class="form-select select-unit-kerja" name="unit_kerja[]"id="unit_kerja{{$index}}">
                                                    <option value="{{ $tindakLanjut->unit_kerja }}">{{ $tindakLanjut->unit_kerja }}</option>
                                                    @foreach ($unit_kerja as $unit)
                                                        <option value="{{ $unit->nama }}">{{ $unit->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 form-group mandatory">
                                                <label class="form-label" for="tim_pemantauan{{$index}}">PIC Tim Pemantauan</label>
                                                <select class="form-select select-tim-pemantauan" name="tim_pemantauan[]">
                                                    <option value="{{ $tindakLanjut->tim_pemantauan }}">{{ $tindakLanjut->tim_pemantauan }}</option>
                                                    <option value="Tim Pemantauan Wilayah I">Tim Pemantauan Wilayah I</option>
                                                    <option value="Tim Pemantauan Wilayah II">Tim Pemantauan Wilayah II</option>
                                                    <option value="Tim Pemantauan Wilayah III">Tim Pemantauan Wilayah III</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 form-group mandatory">
                                                <label class="form-label" for="tenggat_waktu{{$index}}">Tenggat Waktu</label>
                                                <input type="date" class="form-control" name="tenggat_waktu[]" placeholder="Tenggat Waktu"  value="{{ $tindakLanjut->tenggat_waktu }}">
                                            </div>
                                        </div>
                                        <div class="form-row mb-3">
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-danger btn-delete-repeater" onclick="confirmDelete(event)">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 d-flex justify-between justify-content-end mt-2 mb-5" id="formActions">
                <button type="button" class="btn btn-secondary me-3 mb-1" id="btnBack"><i class="bi bi-arrow-left"></i>&nbsp;Back</button>
                <button type="button" class="btn btn-primary me-1 mb-1" id="btnNext">Next&nbsp;<i class="bi bi-arrow-right"></i></button>
                <button type="submit" class="btn btn-primary me-1 mb-1" id="btnTambah">Simpan</button>
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

    // Fungsi untuk menangani klik tombol Tambah
    document.getElementById('btnTambah').addEventListener('click', function() {
        // Lakukan pengiriman data form
        document.getElementById('formTambahRekomendasi').submit();
    });
</script>

<script>
    // Sembunyikan tombol-tombol "Back" dan "Next" secara default
    document.getElementById('btnBack').style.display = 'none';
    document.getElementById('btnNext').style.display = 'none';
    document.getElementById('btnTambah').style.display = 'none';

    // Fungsi untuk menampilkan tombol "Next" pada tab "Pemeriksaan" dan mengatur fungsinya
    function showPemeriksaanActions() {
        document.getElementById('btnNext').style.display = 'block';
        document.getElementById('btnBack').style.display = 'none';
        document.getElementById('btnTambah').style.display = 'none';
        document.getElementById('btnNext').addEventListener('click', function() {
            // Ketika tombol "Next" pada tab "Pemeriksaan" diklik, pindahkan ke tab "Rekomendasi"
            document.getElementById('rekomendasi-tab').click();
        });
    }

    // Fungsi untuk menampilkan tombol "Back" dan "Next" pada tab "Rekomendasi" dan mengatur fungsinya
    function showRekomendasiActions() {
        document.getElementById('btnBack').style.display = 'block';
        document.getElementById('btnNext').style.display = 'block';
        document.getElementById('btnTambah').style.display = 'none';
        document.getElementById('btnBack').addEventListener('click', function() {
            // Ketika tombol "Back" pada tab "Rekomendasi" diklik, pindahkan ke tab "Pemeriksaan"
            document.getElementById('pemeriksaan-tab').click();
        });
        document.getElementById('btnNext').addEventListener('click', function() {
            // Ketika tombol "Next" pada tab "Rekomendasi" diklik, pindahkan ke tab "Tindak Lanjut"
            document.getElementById('tindaklanjut-tab').click();
        });
    }

    // Fungsi untuk menampilkan tombol "Back" dan "Tambah" pada tab "Tindak Lanjut" dan mengatur fungsinya
    function showTindakLanjutActions() {
        document.getElementById('btnBack').style.display = 'block';
        document.getElementById('btnTambah').style.display = 'block';
        document.getElementById('btnNext').style.display = 'none';
        document.getElementById('btnBack').addEventListener('click', function() {
            // Ketika tombol "Back" pada tab "Tindak Lanjut" diklik, pindahkan ke tab "Rekomendasi"
            document.getElementById('rekomendasi-tab').click();
        });
    }

    // Panggil fungsi showPemeriksaanActions() saat halaman dimuat untuk menampilkan tombol "Next" pada awal reload
    window.onload = function() {
        showPemeriksaanActions();
    }

    // Event listener untuk saat tab berubah
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            if (this.getAttribute('aria-controls') === 'pemeriksaan') {
                showPemeriksaanActions(); // Jika tab "Pemeriksaan" aktif, tampilkan tombol "Next" saja
            } else if (this.getAttribute('aria-controls') === 'rekomendasi') {
                showRekomendasiActions(); // Jika tab "Rekomendasi" aktif, tampilkan tombol "Back" dan "Next"
            } else if (this.getAttribute('aria-controls') === 'tindaklanjut') {
                showTindakLanjutActions(); // Jika tab "Tindak Lanjut" aktif, tampilkan tombol "Back" dan "Tambah"
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Fungsi untuk menghitung jumlah form repeater yang tersedia
    function countRepeater() {
        var repeaterCount = document.querySelectorAll('.form-repeater').length;
        document.getElementById('repeaterCount').textContent = repeaterCount;
    }

    // Event listener untuk menambahkan formulir tindak lanjut baru
    document.querySelector('.btn-tambah-repeater').addEventListener('click', function() {
        var formContainer = document.getElementById('formContainer');
        var formItem = `
            <div class="form-repeater mb-3">
                <div class="form-row mb-3">
                    <div class="col-12 form-group mandatory">
                        <label class="form-label" for="tindak_lanjut">Tindak Lanjut</label>
                        <textarea class="form-control" rows="3" name="tindak_lanjut[]" placeholder="Tindak lanjut" data-parsley-required="true" required></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3 form-group mandatory">
                        <label class="form-label" for="unit_kerja">PIC Unit Kerja</label>
                        <select class="form-select select-unit-kerja" name="unit_kerja[]">
                            <option value="">Pilih PIC Unit Kerja</option>
                            @foreach ($unit_kerja as $unit)
                            <option value="{{ $unit->nama }}">{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 form-group mandatory">
                        <label class="form-label" for="tim_pemantauan">PIC Tim Pemantauan</label>
                        <select class="form-select select-tim-pemantauan" name="tim_pemantauan[]">
                            <option value="">Pilih PIC Tim Pemantauan</option>
                            <option value="Tim Pemantauan Wilayah I">Tim Pemantauan Wilayah I</option>
                            <option value="Tim Pemantauan Wilayah II">Tim Pemantauan Wilayah II</option>
                            <option value="Tim Pemantauan Wilayah III">Tim Pemantauan Wilayah III</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 form-group mandatory">
                        <label class="form-label" for="tenggat_waktu">Tenggat Waktu</label>
                        <input type="date" class="form-control" name="tenggat_waktu[]" placeholder="Tenggat Waktu">
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-danger btn-delete-repeater" onclick="confirmDelete(event)">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
        formContainer.insertAdjacentHTML('beforeend', formItem);

        // Inisialisasi TinyMCE di textarea yang baru ditambahkan
        tinymce.init({
            selector: "textarea",
            promotion: false,
            height: 185,
            plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            menubar: "table tools",
        });

        // Initialize Select2 after adding the new repeater item
        $('.select-unit-kerja').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Unit Kerja',
        });

        $('.select-tim-pemantauan').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Tim Pemantauan'
        });

        // Mengupdate counter setelah menambahkan repeater baru
        countRepeater();
    });

    // Fungsi untuk menampilkan pesan konfirmasi sebelum menghapus repeater
    function confirmDelete(event) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Anda tidak akan dapat mengembalikan tindakan ini!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna menekan tombol Ya pada pesan konfirmasi, hapus elemen form repeater
                event.target.closest('.form-repeater').remove();
                // Mengupdate counter setelah menghapus repeater
                countRepeater();
            } else {
                // Jika pengguna memilih opsi "Batal", hentikan aksi default (tidak hapus)
                event.preventDefault();
            }
        });
    }

    // Memanggil fungsi countRepeater saat halaman dimuat
    countRepeater();
</script>

{{-- <script>
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
</script> --}}

@endsection
