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
            <a href="/kelola-tindak-lanjut" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                &nbsp;Kembali
            </a>
            <a href="/kelola-tindak-lanjut/{{ $tindak_lanjut->id }}/generate" class="btn btn-primary ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Berita Acara">
                <i class="bi bi-file-earmark-word"></i>
                &nbsp;Generate Berita Acara
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
                @if (($tindak_lanjut->dokumen_tindak_lanjut === null || $tindak_lanjut->dokumen_tindak_lanjut === 'Belum Diunggah!'))
                <button class="btn btn-warning" id="btnStatus">
                    <i class="bi bi-exclamation-triangle"></i>
                    &nbsp;Dokumen Belum Diunggah!
                </button>
                @else
                <button class="btn btn-success" id="btnStatus" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->format('H:i, d M Y') }}">
                    <i class="bi bi-check-square"></i>
                    &nbsp;Dokumen diunggah {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->diffForHumans() }}
                </button>
                @endif
        </div>
    </div>
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
                <div class="tab-pane fade show active" id="pemeriksaan" role="tabpanel" aria-labelledby="pemeriksaan-tab">
                    {{-- <div class="card-header">
                        <h4 class="card-title"><b>Detail Pemeriksaan</b></h4>
                    </div> --}}
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
                                <p>{{ $rekomendasi->hasil_pemeriksaan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="rekomendasi" role="tabpanel" aria-labelledby="rekomendasi-tab">
                    {{-- <div class="card-header">
                        <h4 class="card-title"><b>Detail Rekomendasi</b></h4>
                    </div> --}}
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
                                <p>{{ $rekomendasi->uraian_temuan }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Rekomendasi</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $rekomendasi->rekomendasi }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Catatan Rekomendasi</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $rekomendasi->catatan_rekomendasi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                    {{-- <div class="card-header">
                        <h4 class="card-title"><b>Tindak Lanjut</b></h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Tindak Lanjut</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $tindak_lanjut->tindak_lanjut }}</p>
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
                                <p>{{ \Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu )->format(' d F Y') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Dokumen Tindak Lanjut</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                @if ($tindak_lanjut->dokumen_tindak_lanjut === null || $tindak_lanjut->dokumen_tindak_lanjut === 'Belum Diunggah!')
                                    <p><span class="status-badge bg-warning text-black">{{ $tindak_lanjut->dokumen_tindak_lanjut }}</span></p>
                                @else
                                    <p><span class="status-badge bg-success text-white">{{ $tindak_lanjut->dokumen_tindak_lanjut }}</span></p>
                                @endif
                            </div>
                            @canany(['Unit Kerja', 'Super Admin'])
                            <div class="col-auto d-flex ms-auto">
                                    @if (($tindak_lanjut->dokumen_tindak_lanjut === null || $tindak_lanjut->dokumen_tindak_lanjut === 'Belum Diunggah!'))
                                    <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unggah Dokumen">
                                        <i class="bi bi-plus"></i>
                                        &nbsp;Tambah Dokumen
                                    </button>
                                    @else
                                    <div class="col-auto d-flex ms-auto">
                                        <div class="col-auto">
                                            <a href="{{ asset('uploads/tindak_lanjut/' . $tindak_lanjut->dokumen_tindak_lanjut) }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download Dokumen">
                                                <i class="bi bi-download"></i>
                                                &nbsp;Unduh Dokumen
                                            </a>
                                            <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Dokumen">
                                                <i class="bi bi-pencil"></i>
                                                &nbsp;Ubah Dokumen
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                            </div>
                            @endcan
                        </div>
                        @if ($tindak_lanjut->dokumen_tindak_lanjut === null || $tindak_lanjut->dokumen_tindak_lanjut === 'Belum Diunggah!')
                        @else
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Detail Dokumen Tindak Lanjut</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ $tindak_lanjut->detail_dokumen_tindak_lanjut }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>Diunggah oleh {{ $tindak_lanjut->upload_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at )->format(' d F Y')}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="identifikasi" role="tabpanel" aria-labelledby="identifikasi-tab">
                    @if ($tindak_lanjut->status_tindak_lanjut !== 'Proses')
                    {{-- <div class="card-header">
                        <h4 class="card-title"><b>Hasil Identifikasi</b></h4>
                    </div> --}}
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
                                <p>{{ $tindak_lanjut->catatan_tindak_lanjut }}</p>
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
                                <p>Diidentifikasi oleh {{ $tindak_lanjut->status_tindak_lanjut_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at )->format(' d F Y')}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

<div class="modal fade text-left" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                @if ($tindak_lanjut->dokumen_tindak_lanjut === null || $tindak_lanjut->dokumen_tindak_lanjut === 'Belum Diunggah!')
                <h4 class="modal-title" id="uploadModalLabel">Tambah Dokumen Tindak Lanjut</h4>
                @else
                <h4 class="modal-title" id="uploadModalLabel">Ubah Dokumen Tindak Lanjut</h4>
                @endif
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="/kelola-tindak-lanjut/{{ $tindak_lanjut->id }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('put')

                <!-- input dengan tipe hidden untuk data yang sbeelumnya -->
                <input type="hidden" name="tindak_lanjut" value="{{ $tindak_lanjut->tindak_lanjut }}">
                <input type="hidden" name="unit_kerja" value="{{ $tindak_lanjut->unit_kerja }}">
                <input type="hidden" name="tim_pemantauan" value="{{ $tindak_lanjut->tim_pemantauan }}">
                <input type="hidden" name="tenggat_waktu" value="{{ $tindak_lanjut->tenggat_waktu }}">
                <input type="hidden" name="rekomendasi_id" value="{{ $tindak_lanjut->rekomendasi_id }}">

                    <div class="form-group">
                        <label for="dokumen_tindak_lanjut" class="form-label">Dokumen Tindak Lanjut</label>
                        <input type="file" class="multiple-files-filepond" multiple name="dokumen_tindak_lanjut">
                    </div>
                    <div class="form-group">
                        <label for="dokumen_tindak_lanjut" class="form-label">Detail Dokumen Tindak Lanjut</label>
                        <div class="card-body">
                            <textarea class="form-control" name="detail_dokumen_tindak_lanjut" id="detail_dokumen_tindak_lanjut" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    @if ($tindak_lanjut->dokumen_tindak_lanjut === null || $tindak_lanjut->dokumen_tindak_lanjut === 'Belum Diunggah!')
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
