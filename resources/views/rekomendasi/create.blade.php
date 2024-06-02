@extends('layouts.horizontal')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('section')
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pemeriksaan-tab" data-bs-toggle="tab" href="#pemeriksaan"
                                    role="tab" aria-controls="pemeriksaan" aria-selected="true">
                                    <h6>Pemeriksaan</h6>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="rekomendasi-tab" data-bs-toggle="tab" href="#rekomendasi"
                                    role="tab" aria-controls="rekomendasi" aria-selected="false">
                                    <h6>Rekomendasi</h6>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="lhp-tab" data-bs-toggle="tab" href="#lhp" role="tab"
                                    aria-controls="lhp" aria-selected="false">
                                    <h6>Dokumen LHP</h6>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tindaklanjut-tab" data-bs-toggle="tab" href="#tindaklanjut"
                                    role="tab" aria-controls="tindaklanjut" aria-selected="false">
                                    <h6>Tindak Lanjut</h6>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <form class="form form-vertical" action="/rekomendasi" method="post" data-parsley-validate
                        id="formTambahRekomendasi" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="pemeriksaan" role="tabpanel"
                                    aria-labelledby="pemeriksaan-tab">
                                    <input type="hidden" name="status_rekomendasi" value="Belum Sesuai">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label class="form-label" for="pemeriksaan">Pemeriksaan</label>
                                            <input type="text" id="pemeriksaan" class="form-control" name="pemeriksaan"
                                                placeholder="Nama Pemeriksaan" data-parsley-required="true" required
                                                value="{{ old('pemeriksaan') }}">
                                            @error('pemeriksaan')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label class="form-label" for="tahun_pemeriksaan">Tahun Pemeriksaan</label>
                                            <select id="tahun_pemeriksaan" class="form-control" name="tahun_pemeriksaan"
                                                data-parsley-required="true" required>
                                                <option value="">Pilih Tahun Pemeriksaan</option>
                                                @for ($year = date('Y'); $year >= 2000; $year--)
                                                    <option value="{{ $year }}" {{ old('tahun_pemeriksaan') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endfor
                                            </select>
                                            @error('tahun_pemeriksaan')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label class="form-label" for="jenis_pemeriksaan">Jenis Pemeriksaan</label>
                                            <select class="form-select" id="jenis_pemeriksaan" name="jenis_pemeriksaan"
                                                data-parsley-required="true" required>
                                                <option value="">Pilih Jenis Pemeriksaan</option>
                                                @foreach ($kamus_pemeriksaan as $kamus)
                                                    <option value="{{ $kamus->nama }}"
                                                        {{ old('jenis_pemeriksaan') == $kamus->nama ? 'selected' : '' }}>
                                                        {{ $kamus->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('jenis_pemeriksaan')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="rekomendasi" role="tabpanel"
                                    aria-labelledby="rekomendasi-tab">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label class="form-label" for="jenis_temuan">Jenis Temuan</label>
                                            <select class="form-select" id="jenis_temuan" name="jenis_temuan"
                                                data-parsley-required="true" required>
                                                <option value="">Pilih Jenis Temuan</option>
                                                @foreach ($kamus_temuan as $kamus)
                                                    <option value="{{ $kamus->nama }}"
                                                        {{ old('jenis_temuan') == $kamus->nama ? 'selected' : '' }}>
                                                        {{ $kamus->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('jenis_temuan')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label class="form-label" for="uraian_temuan">Uraian Temuan</label>
                                            <textarea class="form-control" id="uraian_temuan" rows="3" name="uraian_temuan" placeholder="Uraian Temuan"
                                                data-parsley-required="true" required>{{ old('uraian_temuan') }}</textarea>
                                            @error('uraian_temuan')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label class="form-label" for="rekomendasi">Rekomendasi</label>
                                            <textarea class="form-control" id="rekomendasi" class="form-control" name="rekomendasi" placeholder="Rekomendasi"
                                                data-parsley-required="true" required rows="3">{{ old('rekomendasi') }}</textarea>
                                            @error('rekomendasi')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label class="form-label" for="catatan_rekomendasi">Catatan Rekomendasi
                                                BPK</label>
                                            <textarea class="form-control" id="catatan_rekomendasi" rows="3" name="catatan_rekomendasi"
                                                placeholder="Catatan Rekomendasi" data-parsley-required="true" required>{{ old('catatan_rekomendasi') }}</textarea>
                                            @error('catatan_rekomendasi')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="lhp" role="tabpanel" aria-labelledby="lhp-tab">
                                    <div class="form-group mandatory">
                                        <label for="lhp" class="form-label">Dokumen Laporan Hasil Pemeriksaan
                                            (LHP)</label>
                                        <input type="file" class="basic-filepond" name="lhp" id="lhp"
                                            required>
                                        @error('lhp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tindaklanjut" role="tabpanel"
                                    aria-labelledby="tindaklanjut-tab">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-warning counter" disabled>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-clipboard-data me-2 mb-3"></i>
                                                    <h6 class="mb-0 text-black">Jumlah Rencana Tindak Lanjut: <span
                                                            id="repeaterCount">0</span></h6>
                                                </div>
                                            </button>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <button type="button" class="btn btn-primary btn-tambah-repeater">
                                                <i class="bi bi-plus"></i><span class="d-none d-md-inline">&nbsp;Tambah Rencana
                                                    Tindak Lanjut</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="formContainer">
                                        <div class="form-repeater mb-4">
                                            <div class="divider" id="dividerText">
                                                <div class="divider-text">
                                                    <strong>Rencana Tindak Lanjut <span id="formRepeaterCount">0</span></strong>
                                                </div>
                                            </div>
                                            <div class="form-row mb-3">
                                                <div class="col-12 form-group mandatory">
                                                    <label class="form-label" for="tindak_lanjut">Rencana Tindak Lanjut</label>
                                                    <textarea class="form-control" rows="3" name="tindak_lanjut[]" placeholder="Rencana Tindak lanjut"
                                                        data-parsley-required="true" required>{{ old('tindak_lanjut.0') }}</textarea>
                                                    @error('tindak_lanjut.0')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3 form-group mandatory">
                                                    <label class="form-label" for="unit_kerja">PIC Unit Kerja</label>
                                                    <select class="form-select select-unit-kerja" name="unit_kerja[]">
                                                        <option value="">Pilih PIC Unit Kerja</option>
                                                        @foreach ($unit_kerja as $unit)
                                                            <option value="{{ $unit->nama }}"
                                                                {{ old('unit_kerja.0') == $unit->nama ? 'selected' : '' }}>
                                                                {{ $unit->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('unit_kerja.0')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3 form-group mandatory">
                                                    <label class="form-label" for="tim_pemantauan">PIC Tim
                                                        Pemantauan</label>
                                                    <select class="form-select select-tim-pemantauan"
                                                        name="tim_pemantauan[]">
                                                        <option value="">Pilih PIC Tim Pemantauan</option>
                                                        <option value="Tim Pemantauan Wilayah I"
                                                            {{ old('tim_pemantauan.0') == 'Tim Pemantauan Wilayah I' ? 'selected' : '' }}>
                                                            Tim Pemantauan Wilayah I</option>
                                                        <option value="Tim Pemantauan Wilayah II"
                                                            {{ old('tim_pemantauan.0') == 'Tim Pemantauan Wilayah II' ? 'selected' : '' }}>
                                                            Tim Pemantauan Wilayah II</option>
                                                        <option value="Tim Pemantauan Wilayah III"
                                                            {{ old('tim_pemantauan.0') == 'Tim Pemantauan Wilayah III' ? 'selected' : '' }}>
                                                            Tim Pemantauan Wilayah III</option>
                                                    </select>
                                                    @error('tim_pemantauan.0')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3 form-group mandatory">
                                                    <label class="form-label" for="tenggat_waktu">Tenggat Waktu</label>
                                                    <input type="date" class="form-control flatpickr-no-config"
                                                        name="tenggat_waktu[]" placeholder="Tenggat Waktu"
                                                        value="{{ old('tenggat_waktu.0') }}">
                                                    @error('tenggat_waktu.0')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-row mb-3">
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn btn-danger btn-delete-repeater"
                                                        onclick="confirmDelete(event)">
                                                        <i class="bi bi-trash"></i><span
                                                            class="d-none d-md-inline">&nbsp;Hapus</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 d-flex justify-between justify-content-end mt-2 mb-5" id="formActions">
                    <button type="button" class="btn btn-secondary me-3 mb-1" id="btnBack"><i
                            class="bi bi-arrow-left"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-primary me-1 mb-1" id="btnNext">Next&nbsp;<i
                            class="bi bi-arrow-right"></i></button>
                    <button type="submit" class="btn btn-primary me-1 mb-1" id="btnTambah">Tambah</button>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    <!-- filepond js -->
    <script>
        FilePond.registerPlugin(
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType,
            FilePondPluginImagePreview,
            FilePondPluginImageResize,
            FilePondPluginImageExifOrientation,
            FilePondPluginImageCrop,
            FilePondPluginImageFilter
        );

        FilePond.setOptions({
            credits: false,
            allowMultiple: false,
            maxFiles: 1,
            allowFileTypeValidation: true,
            acceptedFileTypes: [
                'application/pdf',
            ],
            fileValidateTypeLabelExpectedTypes: 'Hanya menerima file PDF.',
            fileValidateTypeLabelExpectedTypesMap: {
                'application/pdf': '.pdf',
            },
            allowFileSizeValidation: true,
            maxFileSize: '100MB',
            labelIdle: 'Seret & Letakkan file atau <span class="filepond--label-action"> Telusuri </span>',
            labelFileProcessing: 'Sedang memproses',
            labelFileProcessingComplete: 'Proses selesai',
            labelTapToCancel: 'tap untuk membatalkan',
            labelTapToRetry: 'tap untuk mencoba lagi',
            labelTapToUndo: 'tap untuk membatalkan',
            labelButtonRemoveItem: 'Hapus',
            labelButtonAbortItemLoad: 'Batal',
            labelButtonRetryItemLoad: 'Coba lagi',
            labelButtonAbortItemProcessing: 'Batal',
            labelButtonUndoItemProcessing: 'Kembali',
            labelButtonRetryItemProcessing: 'Coba lagi',
            labelButtonProcessItem: 'Unggah',
            labelMaxFileSizeExceeded: 'Ukuran file terlalu besar',
            labelMaxFileSize: 'Ukuran file maksimum adalah {filesize}',
            labelMaxTotalFileSizeExceeded: 'Ukuran total file terlalu besar',
            labelMaxTotalFileSize: 'Ukuran total file maksimum adalah {filesize}',
            fileValidateTypeLabelExpectedTypes: 'Hanya menerima file PDF',
        });

        FilePond.parse(document.body);
    </script>
    <!-- select2 js -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Inisialisasi Select2 di select yang ada
        $('.select-unit-kerja').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Unit Kerja',
        });

        $('.select-tim-pemantauan').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih PIC Tim Pemantauan'
        });

        // Inisialisasi select js pada tahun_pemeriksaan
        $('#tahun_pemeriksaan').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Tahun Pemeriksaan',
        });
    </script>
    <!-- flatpickr js -->
    <script>
        flatpickr('.flatpickr-no-config', {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            minDate: "today",
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                },
                months: {
                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September',
                        'Oktober', 'November', 'Desember'
                    ],
                },
            },
        });
    </script>
    <!-- tooltip js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);

        // Fungsi untuk menangani klik tombol Tambah
        document.getElementById('btnTambah').addEventListener('click', function() {
            // Lakukan pengiriman data form
            document.getElementById('formTambahRekomendasi').submit();
        });
    </script>
    <!-- button actions js -->
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
                // Ketika tombol "Next" pada tab "Rekomendasi" diklik, pindahkan ke tab "LHP"
                document.getElementById('lhp-tab').click();
            });
        }

        function showLHPActions() {
            document.getElementById('btnBack').style.display = 'block';
            document.getElementById('btnNext').style.display = 'block';
            document.getElementById('btnTambah').style.display = 'none';
            document.getElementById('btnBack').addEventListener('click', function() {
                // Ketika tombol "Back" pada tab "LHP" diklik, pindahkan ke tab "Rekomendasi"
                document.getElementById('rekomendasi-tab').click();
            });
            document.getElementById('btnNext').addEventListener('click', function() {
                // Ketika tombol "Next" pada tab "LHP" diklik, pindahkan ke tab "Tindak Lanjut"
                document.getElementById('tindaklanjut-tab').click();
            });
        }

        // Fungsi untuk menampilkan tombol "Back" dan "Tambah" pada tab "Tindak Lanjut" dan mengatur fungsinya
        function showTindakLanjutActions() {
            document.getElementById('btnBack').style.display = 'block';
            document.getElementById('btnTambah').style.display = 'block';
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnBack').addEventListener('click', function() {
                // Ketika tombol "Back" pada tab "Tindak Lanjut" diklik, pindahkan ke tab "LHP"
                document.getElementById('lhp-tab').click();
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
                    showRekomendasiActions
                (); // Jika tab "Rekomendasi" aktif, tampilkan tombol "Back" dan "Next"
                } else if (this.getAttribute('aria-controls') === 'lhp') {
                    showLHPActions(); // Jika tab "LHP" aktif, tampilkan tombol "Back" dan "Next"
                } else if (this.getAttribute('aria-controls') === 'tindaklanjut') {
                    showTindakLanjutActions
                (); // Jika tab "Tindak Lanjut" aktif, tampilkan tombol "Back" dan "Tambah"
                }
            });
        });
    </script>
    <!-- repeater js -->
    <script>
        // Fungsi untuk menghitung jumlah form repeater yang tersedia
        function countRepeater() {
            var repeaterCount = document.querySelectorAll('.form-repeater').length;
            document.getElementById('repeaterCount').textContent = repeaterCount;
        }

        function countFormRepeater() {
            var repeaters = document.querySelectorAll('.form-repeater');
            repeaters.forEach(function (repeater, index) {
                var formRepeaterCount = repeater.querySelector('.divider-text #formRepeaterCount');
                if (formRepeaterCount) {
                    formRepeaterCount.textContent = index + 1;
                }
            });
        }

        // Event listener untuk menambahkan formulir tindak lanjut baru
        document.querySelector('.btn-tambah-repeater').addEventListener('click', function() {
            var formContainer = document.getElementById('formContainer');
            var formItem = `
            <div class="form-repeater mb-4">
                <div class="divider" id="dividerText">
                    <div class="divider-text">
                        <strong>Rencana Tindak Lanjut <span id="formRepeaterCount">0</span></strong>
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-12 form-group mandatory">
                        <label class="form-label" for="tindak_lanjut">Rencana Tindak Lanjut</label>
                        <textarea class="form-control" rows="3" name="tindak_lanjut[]" placeholder="Rencana Tindak lanjut" data-parsley-required="true" required></textarea>
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
                        <input type="date" class="form-control flatpickr-no-config" name="tenggat_waktu[]" placeholder="Tenggat Waktu">
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-danger btn-delete-repeater" onclick="confirmDelete(event)">
                            <i class="bi bi-trash"></i><span class="d-none d-md-inline">&nbsp;Hapus</span>
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

            // initialize flatpickr after adding the new repeater item
            flatpickr('.flatpickr-no-config', {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt',
                            'Nov', 'Des'
                        ],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                            'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ],
                    },
                },
            });

            // Mengupdate counter setelah menambahkan repeater baru
            countRepeater();
            countFormRepeater();
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
                    countFormRepeater();
                } else {
                    // Jika pengguna memilih opsi "Batal", hentikan aksi default (tidak hapus)
                    event.preventDefault();
                }
            });
        }

        // Memanggil fungsi countRepeater saat halaman dimuat
        countRepeater();
        countFormRepeater();
    </script>
@endsection
