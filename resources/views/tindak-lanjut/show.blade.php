@extends('layouts.horizontal')

@section('style')

<link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond/filepond.css')}}" />
<link rel="stylesheet" href="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.css') }}"/>

<style>

    .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

    .status-identifikasi {
        background-color: #FFD700;
        color: #000000;
    }

    .status-belum-sesuai {
        background-color: #FFD700;
        color: #000000;
    }

    .status-belum-ditindaklanjuti {
        background-color: #FF0000;
        color: #FFFFFF;
    }

    .status-sesuai {
        background-color: #008000;
        color: #FFFFFF;
    }

    .status-tidak-ditindaklanjuti {
        background-color: #000000;
        color: #FFFFFF;
    }

</style>
@endsection

@section('section')

<section class="row">
    <div class="row mb-3 flex-wrap">
        <div class="col-auto d-flex me-auto">
            <a href="/tindak-lanjut" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-md-inline">&nbsp;Kembali</span>
            </a>
            <a href="/tindak-lanjut/{{ $tindak_lanjut->id }}/generate" class="btn btn-primary ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Berita Acara">
                <i class="bi bi-file-earmark-word"></i>
                <span class="d-none d-md-inline">&nbsp;Generate Berita Acara</span>
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
            <div class="col-auto">
                @if (($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!'))
                    <button class="btn btn-warning" id="btnStatusBukti">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span class="d-none d-md-inline">&nbsp;Bukti Belum Diunggah!</span>
                    </button>
                @else
                    <button class="btn btn-success" id="btnStatusBukti" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->translatedFormat('H:i, d M Y') }}">
                        <i class="bi bi-check-square"></i>
                        <span class="d-none d-md-inline">&nbsp;Bukti Diunggah {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->diffForHumans() }}</span>
                    </button>
                @endif
                @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                @else
                    @if (($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi'))
                        <button class="btn btn-warning ms-2" id="btnStatusIdentifikasi">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span class="d-none d-md-inline">&nbsp;Tindak Lanjut Belum Diidentifikasi!</span>
                        </button>
                    @else
                        <button class="btn btn-success ms-2" id="btnStatusIdentifikasi" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at)->translatedFormat('H:i, d M Y') }}">
                            <i class="bi bi-check-square"></i>
                            <span class="d-none d-md-inline">&nbsp;Diidentifikasi {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at)->diffForHumans() }}</span>
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pemeriksaan-tab" data-bs-toggle="tab" href="#pemeriksaan" role="tab"
                        aria-controls="pemeriksaan" aria-selected="true"><h6>Pemeriksaan</h6></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="rekomendasi-tab" data-bs-toggle="tab" href="#rekomendasi" role="tab"
                        aria-controls="rekomendasi" aria-selected="false"><h6>Rekomendasi</h6></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="tindaklanjut-tab" data-bs-toggle="tab" href="#tindaklanjut" role="tab"
                        aria-controls="tindaklanjut" aria-selected="false"><h6>Tindak Lanjut</h6></a>
                </li>
                @if ($tindak_lanjut->status_tindak_lanjut !== 'Proses')
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="identifikasi-tab" data-bs-toggle="tab" href="#identifikasi" role="tab"
                        aria-controls="identifikasi" aria-selected="false"><h6>Hasil Identifikasi</h6></a>
                </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="pemeriksaan" role="tabpanel" aria-labelledby="pemeriksaan-tab">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Pemeriksaan</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $rekomendasi->pemeriksaan }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Tahun</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $rekomendasi->tahun_pemeriksaan }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Jenis Pemeriksaan</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $rekomendasi->jenis_pemeriksaan }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Hasil Pemeriksaan</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $rekomendasi->hasil_pemeriksaan !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="rekomendasi" role="tabpanel" aria-labelledby="rekomendasi-tab">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Jenis Temuan</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $rekomendasi->jenis_temuan }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Uraian Temuan</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $rekomendasi->uraian_temuan !!}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Rekomendasi</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $rekomendasi->rekomendasi !!}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Catatan Rekomendasi</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $rekomendasi->catatan_rekomendasi !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Tindak Lanjut</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $tindak_lanjut->tindak_lanjut !!}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Unit Kerja</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $tindak_lanjut->unit_kerja }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Tim Pemantauan</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $tindak_lanjut->tim_pemantauan }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Tenggat Waktu</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ \Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu )->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Bukti Tindak Lanjut</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                                    <p><span class="status-badge bg-warning text-black">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span></p>
                                @else
                                    <p><span class="status-badge bg-success text-white">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span></p>
                                @endif
                            </div>
                            @canany(['Operator Unit Kerja', 'Super Admin'])
                            <div class="col-auto d-flex ms-auto">
                                    @if (($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!'))
                                    <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unggah Bukti TL">
                                        <i class="bi bi-plus"></i>
                                        <span class="d-none d-md-inline">&nbsp;Tambah Bukti</span>
                                    </button>
                                    @else
                                    <div class="col-auto d-flex ms-auto">
                                        <div class="col-auto">
                                            <a href="{{ asset('uploads/tindak_lanjut/' . $tindak_lanjut->bukti_tindak_lanjut) }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download Bukti TL">
                                                <i class="bi bi-download"></i>
                                                <span class="d-none d-md-inline"> &nbsp;Unduh Bukti</span>
                                            </a>
                                            <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Bukti TL">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-md-inline">&nbsp;Ubah Bukti</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                            </div>
                            @endcan
                        </div>
                        @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                        @else
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Detail Bukti Tindak Lanjut</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $tindak_lanjut->detail_bukti_tindak_lanjut !!}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>Diunggah oleh {{ $tindak_lanjut->upload_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at )->translatedFormat('d M Y')}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="identifikasi" role="tabpanel" aria-labelledby="identifikasi-tab">
                    @if ($tindak_lanjut->bukti_tindak_lanjut !== 'Belum Diunggah!')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Status Identifikasi</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p><span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }}">{{ $tindak_lanjut->status_tindak_lanjut }}</span></p>
                            </div>
                        </div>
                        @if ($tindak_lanjut->catatan_tindak_lanjut === '' || $tindak_lanjut->catatan_tindak_lanjut === null)
                        @else
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Catatan Identifikasi</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $tindak_lanjut->catatan_tindak_lanjut !!}</p>
                            </div>
                        </div>
                        @endif
                        @if ($tindak_lanjut->status_tindak_lanjut !== 'Identifikasi')
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>Diidentifikasi oleh {{ $tindak_lanjut->status_tindak_lanjut_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at )->translatedFormat('d M Y')}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <!-- akan muncul peringatan untuk mengunggah bukti tindak lanjut terlebih dahulu -->
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Peringatan!</h4>
                        <p>Anda belum dapat melihat hasil identifikasi karena bukti tindak lanjut belum diunggah.</p>
                        <hr>
                        <p class="mb-0">Silakan unggah bukti tindak lanjut terlebih dahulu.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

<div class="modal fade text-left" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                <h4 class="modal-title" id="uploadModalLabel">Tambah Bukti Tindak Lanjut</h4>
                @else
                <h4 class="modal-title" id="uploadModalLabel">Ubah Bukti Tindak Lanjut</h4>
                @endif
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="/tindak-lanjut/{{ $tindak_lanjut->id }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="form-group mandatory">
                        <label for="bukti_tindak_lanjut" class="form-label">Bukti Tindak Lanjut (.pdf/.zip/.rar/.tar)</label>
                        <input type="file" class="multiple-files-filepond" multiple name="bukti_tindak_lanjut" required>
                        @error('bukti_tindak_lanjut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mandatory">
                        <label for="bukti_tindak_lanjut" class="form-label">Detail Bukti Tindak Lanjut</label>
                        <div class="card-body">
                            <textarea class="form-control" name="detail_bukti_tindak_lanjut" id="detail_bukti_tindak_lanjut" rows="3" required data-parsley-required="true"></textarea>
                        @error('detail_bukti_tindak_lanjut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                    <button type="submit" class="btn btn-primary ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tambah</span>
                    </button>
                    @else
                    <button type="submit" class="btn btn-primary ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Ubah</span>
                    </button>
                    @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('script')

<script>
    $(document).ready(function() {
        // Fungsi untuk menyembunyikan semua tombol status
        function hideAllButtons() {
            $('#btnStatusIdentifikasi').hide();
        }

        // Pemanggilan fungsi hideAllButtons() saat halaman dimuat
        hideAllButtons();

        // Fungsi untuk menampilkan tombol status identifikasi
        function showIdentifikasi() {
            $('#btnStatusIdentifikasi').show();
            $('#btnStatusBukti').hide();
        }

        // Fungsi untuk menampilkan tombol status bukti tindak lanjut
        function showBukti() {
            $('#btnStatusBukti').show();
        }

        // Memanggil fungsi yang sesuai berdasarkan tab yang aktif
        $('.nav-link').on('shown.bs.tab', function (e) {
            var activeTabId = $(e.target).attr('aria-controls');

            if (activeTabId === 'identifikasi') {
                hideAllButtons();
                $('#btnStatusBukti').hide();
                showIdentifikasi();
            } else if (activeTabId === 'tindaklanjut') {
                hideAllButtons();
                showBukti();
            } else {
                hideAllButtons();
                $('#btnStatusBukti').hide();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
</script>

<script>
    // Tangani klik tombol
    document.getElementById('btnStatus').addEventListener('click', function() {
    // Dapatkan elemen target yang ingin diarahkan atau di-scroll
    var uploadSection = document.getElementById('uploadBtn');

    // Lakukan scroll ke elemen target
    uploadSection.scrollIntoView({ behavior: 'smooth' });
    });
</script>

<script>
    // Ambil tombol "Upload Dokumen TL"
    var uploadBtn = document.getElementById('uploadBtn');

    // Tambahkan event listener untuk menampilkan modal saat tombol diklik
    uploadBtn.addEventListener('click', function() {
        var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        uploadModal.show();
    });

</script>

<script src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/filepond/filepond.js') }}"></script>
<script src="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.js') }}"></script>
<script src="{{ asset('mazer/assets/static/js/pages/filepond.js') }}"></script>

<script>
    @if (session()->has('update'))
        Swal.fire({
            title: 'Success',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
            text: '{{ session('update') }}'
    });
    @endif
</script>

@endsection


@php
function getStatusClass($status) {
    switch ($status) {
        case 'Identifikasi':
            return 'status-identifikasi';
            break;
        case 'Belum Sesuai':
            return 'status-belum-sesuai';
            break;
        case 'Belum Sesuai':
            return 'status-belum-sesuai';
            break;
        case 'Sesuai':
            return 'status-sesuai';
            break;
        case 'Tidak Ditindaklanjuti':
            return 'status-tidak-ditindaklanjuti';
            break;
        default:
            return '';
    }
}
@endphp
