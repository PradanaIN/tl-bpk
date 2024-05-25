@extends('layouts.horizontal')

@section('style')
<link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond/filepond.css') }}">
@endsection

@php
    function getStatusClass($status) {
        $statusClasses = [
            'Identifikasi' => 'status-identifikasi',
            'Belum Ditindaklanjuti' => 'status-belum-ditindaklanjuti',
            'Belum Sesuai' => 'status-belum-sesuai',
            'Sesuai' => 'status-sesuai',
            'Tidak Ditindaklanjuti' => 'status-tidak-ditindaklanjuti',
        ];

        return $statusClasses[$status] ?? '';
    }
@endphp


@section('section')

<section class="row">
    <div class="row mb-3 flex-wrap">
        <div class="col-auto d-flex me-auto">
            <a href="/identifikasi" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-md-inline">&nbsp;Kembali</span>
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
            @if (($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!'))
            <button class="btn btn-outline-warning" id="btnStatusBukti">
                <i class="bi bi-exclamation-triangle text-black"></i>
                <span class="d-none d-md-inline text-black">&nbsp;Bukti Belum Diunggah!</span>
            </button>
            @else
            <button class="btn btn-outline-success" id="btnStatusBukti" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->translatedFormat('H:i, d M Y') }}">
                <i class="bi bi-check-square"></i>
                <span class="d-none d-md-inline">&nbsp;Bukti Diunggah {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->diffForHumans() }}</span>
            </button>
            @endif
            @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
            @else
                @if (($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi'))
                <button class="btn btn-outline-warning" id="btnStatusIdentifikasi">
                    <i class="bi bi-exclamation-triangle text-black"></i>
                    <span class="d-none d-md-inline text-black">&nbsp;Tindak Lanjut Belum Diidentifikasi!</span>
                </button>
                @else
                <button class="btn btn-outline-success" id="btnStatusIdentifikasi" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at)->translatedFormat('H:i, d M Y')  }}">
                    <i class="bi bi-check-square"></i>
                    <span class="d-none d-md-inline">&nbsp;Diidentifikasi {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at)->diffForHumans() }}</span>
                </button>
                @endif
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pemeriksaan-tab" data-bs-toggle="tab" href="#pemeriksaan" role="tab" aria-controls="pemeriksaan" aria-selected="true"><h6>Pemeriksaan</h6></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="rekomendasi-tab" data-bs-toggle="tab" href="#rekomendasi" role="tab" aria-controls="rekomendasi" aria-selected="false"><h6>Rekomendasi</h6></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ ($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi') ? 'active' : '' }}" id="tindaklanjut-tab" data-bs-toggle="tab" href="#tindaklanjut" role="tab" aria-controls="tindaklanjut" aria-selected="false"><h6>Tindak Lanjut</h6></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ ($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi') ? '' : 'active' }}" id="identifikasi-tab" data-bs-toggle="tab" href="#identifikasi" role="tab" aria-controls="identifikasi" aria-selected="{{ ($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi') ? 'true' : 'false' }}"><h6>Hasil Identifikasi</h6></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="pemeriksaan" role="tabpanel" aria-labelledby="pemeriksaan-tab">
                    <div class="card-body">
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Pemeriksaan</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ $rekomendasi->pemeriksaan }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Tahun</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ $rekomendasi->tahun_pemeriksaan }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Jenis Pemeriksaan</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ $rekomendasi->jenis_pemeriksaan }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Hasil Pemeriksaan</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($rekomendasi->hasil_pemeriksaan)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="rekomendasi" role="tabpanel" aria-labelledby="rekomendasi-tab">
                    <div class="card-body">
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Jenis Temuan</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ $rekomendasi->jenis_temuan }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Uraian Temuan</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($rekomendasi->uraian_temuan)) }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Rekomendasi</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($rekomendasi->rekomendasi)) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Catatan Rekomendasi</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($rekomendasi->catatan_rekomendasi)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {{ ($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi') ? 'show active' : '' }}" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                    <div class="card-body">
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Tindak Lanjut</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($tindak_lanjut->tindak_lanjut)) }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Unit Kerja</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ $tindak_lanjut->unit_kerja }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Tim Pemantauan</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ $tindak_lanjut->tim_pemantauan }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Tenggat Waktu</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ \Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu )->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Bukti Tindak Lanjut</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                                    <p><span class="status-badge bg-warning text-black">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span></p>
                                @else
                                    <div class="col-auto d-flex ms-auto">
                                        <span class="status-badge bg-success text-white me-2">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span>
                                        @canany(['Tim Koordinator Wilayah I', 'Tim Koordinator Wilayah II', 'Tim Koordinator Wilayah III', 'Super Admin'])
                                            <a href="{{ asset('uploads/tindak_lanjut/' . $tindak_lanjut->bukti_tindak_lanjut) }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download Bukti TL">
                                                <i class="bi bi-download"></i>
                                                <span class="d-none d-md-inline">&nbsp;Unduh Bukti</span>
                                            </a>
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($tindak_lanjut->bukti_tindak_lanjut !== 'Belum Diunggah!')
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Detail Bukti Tindak Lanjut</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($tindak_lanjut->detail_bukti_tindak_lanjut)) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>Diunggah oleh {{ $tindak_lanjut->upload_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at )->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        @endif
                        @if (($tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!') && (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu))))
                        <div class="alert alert-warning mt-3" role="alert">
                            <h4 class="alert-heading">Info!</h4>
                            <p class="mb-0">Bukti tindak lanjut belum diunggah oleh Unit Kerja dan sudah melewati tenggat waktu!.</p>
                            <hr>
                            <p class="mb-0">Idnetifikasi bisa dilakukan dengan memberikan status <strong>Belum Ditindaklanjuti</strong>.</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade {{ ($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi') ? '' : 'show active' }}" id="identifikasi" role="tabpanel" aria-labelledby="identifikasi-tab">
                    @if (($tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!') && (\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu))))
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Peringatan!</h4>
                            <p class="mb-0">Anda belum dapat melakukan identifikasi tindak lanjut karena bukti tindak lanjut belum diunggah!</p>
                            <hr>
                            <p class="mb-0">Identifikasi dapat dilakukan setelah unit kerja mengunggah bukti tindak lanjut.</p>
                        </div>
                    @elseif (($tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!') && (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu))))
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Status Identifikasi</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <div class="col-auto d-flex align-items-center">
                                        <span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }} me-2">{{ $tindak_lanjut->status_tindak_lanjut }}</span>
                                        @if (($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi'))
                                        <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tambah Hasil Identifikasi">
                                            <i class="bi bi-plus"></i>
                                            <span class="d-none d-md-inline">&nbsp;Tambah Identifikasi</span>
                                        </button>
                                        @else
                                        <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Hasil Identifikasi">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-md-inline">&nbsp;Ubah Identifikasi</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($tindak_lanjut->catatan_tindak_lanjut !== '' && $tindak_lanjut->catatan_tindak_lanjut !== null)
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Catatan Identifikasi</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ strip_tags(html_entity_decode($tindak_lanjut->catatan_tindak_lanjut)) }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($tindak_lanjut->status_tindak_lanjut !== 'Identifikasi')
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Informasi Lainnya</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>Diidentifikasi oleh {{ $tindak_lanjut->status_tindak_lanjut_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at )->translatedFormat('d M Y')}}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Status Identifikasi</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <div class="col-auto d-flex align-items-center">
                                        <span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }} me-2">{{ $tindak_lanjut->status_tindak_lanjut }}</span>
                                        @if (($tindak_lanjut->status_tindak_lanjut === null || $tindak_lanjut->status_tindak_lanjut === 'Identifikasi'))
                                        <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tambah Hasil Identifikasi">
                                            <i class="bi bi-plus"></i>
                                            <span class="d-none d-md-inline">&nbsp;Tambah Identifikasi</span>
                                        </button>
                                        @else
                                        <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Hasil Identifikasi">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-md-inline">&nbsp;Ubah Identifikasi</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($tindak_lanjut->catatan_tindak_lanjut !== '' && $tindak_lanjut->catatan_tindak_lanjut !== null)
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Catatan Identifikasi</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ strip_tags(html_entity_decode($tindak_lanjut->catatan_tindak_lanjut)) }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($tindak_lanjut->status_tindak_lanjut !== 'Identifikasi')
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Informasi Lainnya</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>Diidentifikasi oleh {{ $tindak_lanjut->status_tindak_lanjut_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at )->translatedFormat('d M Y')}}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

<!-- modal upload -->
<div class="modal fade text-left" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Hasil Identifikasi Tindak Lanjut</h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="/identifikasi/{{ $tindak_lanjut->id }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('put')
                    @if(($tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!') && (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu))))
                        <div class="form-group mandatory">
                            <label for="bukti_tindak_lanjut" class="form-label">Hasil Identifikasi</label>
                            <select class="form-select" name="status_tindak_lanjut" id="status_tindak_lanjut" required>
                                <option value="Belum Ditindaklanjuti" {{ $tindak_lanjut->status_tindak_lanjut === 'Belum Ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                            </select>
                        </div>
                    @else
                        <div class="form-group mandatory">
                            <label for="bukti_tindak_lanjut" class="form-label">Hasil Identifikasi</label>
                            <select class="form-select" name="status_tindak_lanjut" id="status_tindak_lanjut" required>
                                <option value="Sesuai" {{ $tindak_lanjut->status_tindak_lanjut === 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                                <option value="Belum Sesuai" {{ $tindak_lanjut->status_tindak_lanjut === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                                <option value="Belum Ditindaklanjuti" {{ $tindak_lanjut->status_tindak_lanjut === 'Belum Ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                                <option value="Tidak Ditindaklanjuti" {{ $tindak_lanjut->status_tindak_lanjut === 'Tidak Ditindaklanjuti' ? 'selected' : '' }}>Tidak Ditindaklanjuti</option>
                            </select>
                        </div>
                    @endif
                    <div class="form-group mandatory" id="catatan_tindak_lanjut_group">
                        <label for="bukti_tindak_lanjut" class="form-label">Catatan Identifikasi</label>
                        <div class="card-body">
                            <textarea class="form-control" name="catatan_tindak_lanjut" id="catatan_tindak_lanjut" rows="5" required>{{ old('catatan_tindak_lanjut', $tindak_lanjut->catatan_tindak_lanjut ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('script')

<script>
$(document).ready(function () {
    function toggleCatatanTindakLanjut() {
        if ($('#status_tindak_lanjut').val() === 'Sesuai') {
            $('#catatan_tindak_lanjut_group').hide();
            $('#catatan_tindak_lanjut').prop('required', false).val('');
        } else {
            $('#catatan_tindak_lanjut_group').show();
            $('#catatan_tindak_lanjut').prop('required', true);
        }
    }

    // Panggil fungsi toggleCatatanTindakLanjut saat dokumen sudah siap
    toggleCatatanTindakLanjut();

    // Panggil fungsi toggleCatatanTindakLanjut saat nilai status_tindak_lanjut berubah
    $('#status_tindak_lanjut').change(function () {
        toggleCatatanTindakLanjut();
    });
});

</script>

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
<script src="{{ asset('mazer/assets/extensions/filepond/filepond.js') }}"></script>
<script src="{{ asset('mazer/assets/static/js/pages/filepond.js') }}"></script>

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
