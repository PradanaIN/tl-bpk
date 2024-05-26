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
        background-color: #FF6347; /* Merah Terang */
        color: #FFFFFF; /* Putih */
    }

    .status-sesuai {
        background-color: #008000; /* Hijau */
        color: #FFFFFF; /* Putih */
    }

    .status-tidak-ditindaklanjuti {
        background-color: #808080; /* Abu-abu */
        color: #FFFFFF; /* Putih */
    }

</style>
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
                @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                    <button class="btn icon btn-outline-warning" id="btnStatusBukti">
                        <i class="bi bi-exclamation-triangle text-black"></i>
                        <span class="d-none d-md-inline text-black">&nbsp;Bukti Belum Diunggah!</span>
                    </button>
                @else
                    <button class="btn btn-outline-success" id="btnStatusBukti" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->translatedFormat('H:i, d M Y') }}">
                        <i class="bi bi-check-square"></i>
                        <span class="d-none d-md-inline">&nbsp;Bukti Diunggah {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->diffForHumans() }}</span>
                    </button>
                @endif
                @if (($tindak_lanjut->status_tindak_lanjut_at === null || $tindak_lanjut->status_tindak_lanjut_at === ''))
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
                <div class="tab-pane fade show active" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
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
                                @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!' || $tindak_lanjut->bukti_tindak_lanjut === '')
                                <div class="col-auto d-flex align-items-center">
                                    <span class="status-badge bg-warning text-black me-2">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span>
                                @canany(['Operator Unit Kerja', 'Super Admin'])
                                    @if (\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu)))
                                        <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unggah Bukti TL">
                                            <i class="bi bi-plus"></i>
                                            <span class="d-none d-md-inline">&nbsp;Tambah Bukti</span>
                                        </button>
                                    @endif
                                @endcan
                                </div>
                                @else
                                    <div class="col-auto d-flex ms-auto">
                                        <span class="status-badge bg-success text-white me-2">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span>
                                        @canany(['Operator Unit Kerja', 'Super Admin'])
                                        <div class="col-auto d-flex align-content-center">
                                            <a href="{{ asset('uploads/tindak_lanjut/' . $tindak_lanjut->bukti_tindak_lanjut) }}" class="btn btn-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unduh Bukti TL">
                                                <i class="bi bi-download"></i>
                                                <span class="d-none d-md-inline">&nbsp;Unduh Bukti</span>
                                            </a>
                                            @if (\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu)))
                                            <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Bukti TL">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-md-inline">&nbsp;Ubah Bukti</span>
                                            </button>
                                            @endif
                                        </div>
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ((\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu))) && ($tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!'))
                            @php
                                $waktuUpload = \Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu)->addDays(1)->translatedFormat('l, d M Y');
                            @endphp
                            @if (Auth::user()->hasRole('Operator Unit Kerja'))
                                <div class="alert alert-warning mt-3" role="alert">
                                    <h4 class="alert-heading">Peringatan!</h4>
                                    <p>Anda telah melewati batas waktu untuk mengunggah bukti tindak lanjut.</p>
                                    <p>&nbsp;Waktu terakhir untuk mengunggah adalah: <strong>{{ $waktuUpload }}.</strong></p>
                                    <hr>
                                    <p class="mb-0"><strong>Silakan hubungi Inspektorat Utama untuk mendapatkan informasi lebih lanjut.</strong></p>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3" role="alert">
                                    <h4 class="alert-heading">Info!</h4>
                                    <p>Unit Kerja telah melewati batas waktu untuk mengunggah bukti tindak lanjut.</p>
                                    <p>&nbsp;Waktu terakhir untuk mengunggah adalah: <strong>{{ $waktuUpload }}.</strong></p>
                                    <hr>
                                    <p class="mb-0"><strong>Anda dapat membuka kembali unggah bukti tindak lanjut dengan mengubah tenggat waktu.</strong></p>
                                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#ubahTenggatWaktuModal">
                                        <i class="bi bi-pencil"></i>
                                        <span class="d-none d-sm-inline">&nbsp;Ubah Tenggat Waktu</span>
                                    </button>
                                </div>
                            @endif
                        @elseif ($tindak_lanjut->bukti_tindak_lanjut !== null && $tindak_lanjut->bukti_tindak_lanjut !== 'Belum Diunggah!' && $tindak_lanjut->bukti_tindak_lanjut !== '')
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
                                    <p>Diunggah oleh {{ $tindak_lanjut->upload_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at )->translatedFormat('d M Y')}}</p>
                                </div>
                            </div>
                            {{-- @if (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu)))
                                <div class="alert alert-warning mt-3" role="alert">
                                    <p>Tidak dapat melakukan pengubahan karena telah melewati batas waktu untuk mengunggah bukti tindak lanjut.</p>
                                    <hr>
                                    <p class="mb-0"><strong>Silakan hubungi Inspektorat Utama untuk mendapatkan informasi lebih lanjut.</strong></p>
                                </div>
                            @endif --}}
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="identifikasi" role="tabpanel" aria-labelledby="identifikasi-tab">
                    @if ((\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu))) && ($tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!'))
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Peringatan!</h4>
                            <p>Anda belum dapat melihat hasil identifikasi karena bukti tindak lanjut belum diunggah.</p>
                            <hr>
                            <p class="mb-0">Silakan unggah bukti tindak lanjut terlebih dahulu.</p>
                        </div>
                    @elseif ((\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu))) && ($tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!'))
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Status Tindak Lanjut</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p><span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }}">{{ $tindak_lanjut->status_tindak_lanjut }}</span></p>
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
                            @if ($tindak_lanjut->status_tindak_lanjut_at !== null)
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                        <p class="fw-bold">Informasi Lainnya</p>
                                    </div>
                                    <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                    <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                        <p>Diidentifikasi oleh {{ $tindak_lanjut->status_tindak_lanjut_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at )->translatedFormat('d M Y')}}</p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3" role="alert">
                                    <p>Tindak lanjut telah melewati batas waktu dan sedang dalam proses identifikasi oleh {{ $tindak_lanjut->tim_pemantauan }}.</p>
                                    <hr>
                                    <p class="mb-0">Silakan tunggu hasil identifikasi oleh {{ $tindak_lanjut->tim_pemantauan }}.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Status Tindak Lanjut</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p><span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }}">{{ $tindak_lanjut->status_tindak_lanjut }}</span></p>
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
                            @if ($tindak_lanjut->status_tindak_lanjut_at !== null)
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                        <p class="fw-bold">Informasi Lainnya</p>
                                    </div>
                                    <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                    <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                        <p>Diidentifikasi oleh {{ $tindak_lanjut->status_tindak_lanjut_by }} pada {{ \Carbon\Carbon::parse($tindak_lanjut->status_tindak_lanjut_at )->translatedFormat('d M Y')}}</p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3" role="alert">
                                    <p>Bukti tindak lanjut sedang dalam proses identifikasi oleh {{ $tindak_lanjut->tim_pemantauan }}.</p>
                                    <hr>
                                    <p class="mb-0">Silakan tunggu hasil identifikasi oleh {{ $tindak_lanjut->tim_pemantauan }}.</p>
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

<!-- Modal Upload Bukti Tindak Lanjut -->
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
                        <input type="file" class="basic-filepond" name="bukti_tindak_lanjut" required>
                        @error('bukti_tindak_lanjut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mandatory">
                        <label for="bukti_tindak_lanjut" class="form-label">Detail Bukti Tindak Lanjut</label>
                        <div class="card-body">
                            <textarea class="form-control" name="detail_bukti_tindak_lanjut" id="detail_bukti_tindak_lanjut" rows="3" required data-parsley-required="true">{{ old('detail_bukti_tindak_lanjut', $tindak_lanjut->detail_bukti_tindak_lanjut ?? '') }}</textarea>
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

<!-- Modal ubah tenggat waktu -->
<div class="modal fade" id="ubahTenggatWaktuModal" tabindex="-1" aria-labelledby="ubahTenggatWaktuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ubahTenggatWaktuModalLabel">Ubah Tenggat Waktu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/tindak-lanjut/{{ $tindak_lanjut->id }}/updateDeadline" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ $tindak_lanjut->id }}">
                <input type="hidden" name="unit_kerja" value="{{ $tindak_lanjut->unit_kerja }}">
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="col-auto form-group mandatory">
                        <label class="form-label" for="tenggat_waktu">Tenggat Waktu Baru</label>
                        <input type="date" class="form-control flatpickr-no-config" name="tenggat_waktu" placeholder="Tenggat Waktu" value="{{ old('tenggat_waktu') }}">
                        @error('tenggat_waktu')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Ubah</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
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

<!-- filepond -->
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
            'application/zip',
            'application/x-rar-compressed',
            'application/x-tar',
            'application/x-7z-compressed',
            'application/x-zip',
            'application/x-zip-compressed',
        ],
        fileValidateTypeLabelExpectedTypes: 'Hanya menerima file PDF, ZIP, RAR, dan TAR',
        fileValidateTypeLabelExpectedTypesMap: {
            'application/pdf': '.pdf',
            'application/zip': '.zip',
            'application/x-rar-compressed': '.rar',
            'application/x-tar': '.tar',
            'application/x-7z-compressed': '.7z',
            'application/x-zip': '.zip',
            'application/x-zip-compressed': '.zip',
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
        fileValidateTypeLabelExpectedTypes: 'Hanya menerima file PDF, ZIP, RAR, dan TAR',
    });

    FilePond.parse(document.body);
</script>

<!-- flatpickr -->
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
                longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            },
        },
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
