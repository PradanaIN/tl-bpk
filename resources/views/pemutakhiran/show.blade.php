@extends('layouts.horizontal')

@section('style')

<link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond/filepond.css')}}" />
<link rel="stylesheet" href="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.css') }}"/>
@endsection

@php
function getStatusClass($status) {
    $statusClasses = [
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
            <a href="/rekomendasi" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-md-inline">Kembali</span>
            </a>
            <a href="/rekomendasi/{{ $rekomendasi->id }}/export" class="btn btn-success ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Export Rekomendasi">
                <i class="bi bi-file-earmark-excel"></i>
                <span class="d-none d-md-inline">Export Rekomendasi</span>
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
            @if ($rekomendasi->buktiInputSIPTL === null || $rekomendasi->buktiInputSIPTL->bukti_input_siptl === 'Belum Diunggah!')
            <button class="btn btn-outline-warning" id="btnStatusBuktiInput">
                <i class="bi bi-exclamation-triangle text-black"></i>
                <span class="d-none d-md-inline text-black">&nbsp;Bukti Belum Diunggah!</span>
            </button>
            @else
            <button class="btn btn-outline-success" id="btnStatusBuktiInput" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($rekomendasi->upload_at)->translatedFormat('H:i, d M Y') }}">
                <i class="bi bi-check-square"></i>
                <span class="d-none d-md-inline">&nbsp;Bukti Diunggah {{ \Carbon\Carbon::parse($rekomendasi->buktiInputSIPTL->upload_at)->diffForHumans() }}</span>
            </button>
            @endif
            @if ($rekomendasi->pemutakhiran_at === null && $rekomendasi->pemutakhiran_at === '' && $rekomendasi->pemutakhiran_by === '')
            <button class="btn btn-outline-warning" id="btnStatusPemutakhiran">
                <i class="bi bi-exclamation-triangle text-black"></i>
                <span class="d-none d-md-inline text-black">&nbsp;Rekomendasi Belum Dimutakhirkan!</span>
            </button>
            @else
            <button class="btn btn-outline-success" id="btnStatusPemutakhiran" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($rekomendasi->pemutakhiran_at)->translatedFormat('H:i, d M Y') }}">
                <i class="bi bi-check-square"></i>
                <span class="d-none d-md-inline">&nbsp;Dimutakhirkan {{ \Carbon\Carbon::parse($rekomendasi->pemutakhiran_at)->diffForHumans() }}</span>
            </button>
            @endif
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
                    <a class="nav-link" id="tindaklanjut-tab" data-bs-toggle="tab" href="#tindaklanjut" role="tab"
                        aria-controls="tindaklanjut" aria-selected="false"><h6>Tindak Lanjut</h6></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="bukti_input_siptl-tab" data-bs-toggle="tab" href="#bukti_input_siptl" role="tab"
                        aria-controls="bukti_input_siptl" aria-selected="false"><h6>Bukti Input SIPTL</h6></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pemutakhiran_status-tab" data-bs-toggle="tab" href="#pemutakhiran_status" role="tab"
                        aria-controls="pemutakhiran_status" aria-selected="false"><h6>Pemutakhiran Status</h6></a>
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
                <div class="tab-pane fade" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="table1">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tindak Lanjut</th>
                                        <th>Unit Kerja</th>
                                        <th>Tim Pemantauan</th>
                                        <th>Tenggat Waktu</th>
                                        <th>Bukti Tindak Lanjut</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekomendasi->tindakLanjut as $index => $tindakLanjut)
                                        <tr>
                                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                                            <td >{{ strip_tags(html_entity_decode($tindakLanjut->tindak_lanjut)) }}</td>
                                            <td >{{ $tindakLanjut->unit_kerja }}</td>
                                            <td>{{ $tindakLanjut->tim_pemantauan }}</td>
                                            <td style="text-align:center;">{{ \Carbon\Carbon::parse($tindakLanjut->tenggat_waktu )->translatedFormat('d M Y') }}</td>
                                            <td style="text-align:center;">
                                                @if ($tindakLanjut->bukti_tindak_lanjut === null || $tindakLanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                                                    <span class="status-badge status-belum-sesuai">Belum Diunggah!</span>
                                                @else
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="status-badge status-sesuai mb-1">{{ $tindakLanjut->bukti_tindak_lanjut }}</span>
                                                        <a href="{{ asset('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut) }}" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download Bukti">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="text-align:center;">
                                                <span class="status-badge {{ getStatusClass($tindakLanjut->status_tindak_lanjut) }}">{{ $tindakLanjut->status_tindak_lanjut }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="bukti_input_siptl" role="tabpanel" aria-labelledby="bukti_input_siptl-tab">
                    <div class="card-body">
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Bukti Input SIPTL</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                @if ($rekomendasi->buktiInputSIPTL === null || $rekomendasi->buktiInputSIPTL->bukti_input_siptl === 'Belum Diunggah!')
                                    <span class="status-badge bg-warning text-black me-2">{{ $rekomendasi->buktiInputSIPTL->bukti_input_siptl }}</span>
                                    @canany(['Tim Koordinator', 'Super Admin'])
                                    <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Upload Bukti Input SIPTL">
                                        <i class="bi bi-upload"></i>
                                        <span class="d-none d-md-inline">&nbsp;Upload Bukti</span>
                                    </button>
                                    @endcan
                                @else
                                    <div class="col-auto d-flex ms-auto">
                                        <span class="status-badge bg-success text-white me-2">{{ $rekomendasi->buktiInputSIPTL->bukti_input_siptl }}</span>
                                        @canany(['Tim Koordinator', 'Super Admin'])
                                        <div class="col-auto d-flex align-content-center">
                                            <a href="{{ asset('uploads/bukti_input_siptl/' . $rekomendasi->buktiInputSIPTL->bukti_input_siptl) }}" class="btn btn-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unduh Bukti Input SIPTL">
                                                <i class="bi bi-download"></i>
                                                <span class="d-none d-md-inline">&nbsp;Unduh Bukti</span>
                                            </a>
                                            <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Bukti Input SIPTL">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-md-inline">&nbsp;Ubah Bukti</span>
                                            </button>
                                        </div>
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($rekomendasi->buktiInputSIPTL !== null && $rekomendasi->buktiInputSIPTL->bukti_input_siptl !== 'Belum Diunggah!')
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Detail Input SIPTL</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($rekomendasi->buktiInputSIPTL->detail_bukti_input_siptl)) }}</p>
                            </div>
                        </div>
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>Diunggah oleh {{ $rekomendasi->buktiInputSIPTL->upload_by }} pada {{ \Carbon\Carbon::parse($rekomendasi->buktiInputSIPTL->upload_at)->translatedFormat('d M Y')}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade show active" id="pemutakhiran_status" role="tabpanel" aria-labelledby="pemutakhiran_status-tab">
                @if ($rekomendasi->buktiInputSIPTL->bukti_input_siptl !== 'Belum Diunggah!')
                    <div class="card-body">
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Status Rekomendasi</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <div class="col-auto d-flex align-items-center">
                                    <span class="status-badge {{ getStatusClass($rekomendasi->status_rekomendasi) }} me-2">{{ $rekomendasi->status_rekomendasi }}</span>
                                    @canany(['Tim Koordinator', 'Super Admin'])
                                        @if ($rekomendasi->pemutakhiran_at === null && $rekomendasi->pemutakhiran_at === '' && $rekomendasi->pemutakhiran_by === '')
                                        <button class="btn btn-primary" id="pemutakhiranBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tambah Status Pemutakhiran">
                                            <i class="bi bi-plus"></i>
                                            <span class="d-none d-md-inline">&nbsp;Tambah Pemutakhiran</span>
                                        </button>
                                        @else
                                        <button class="btn btn-primary" id="pemutakhiranBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Status Pemutakhiran">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-md-inline">&nbsp;Ubah Pemutakhiran</span>
                                        </button>
                                        @endif
                                    @endcanany
                                </div>
                            </div>
                        </div>
                        @if ($rekomendasi->catatan_pemutakhiran !== '' && $rekomendasi->catatan_pemutakhiran !== null)
                        <div class="row custom-row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Catatan Pemutakhiran</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>{{ strip_tags(html_entity_decode($rekomendasi->catatan_pemutakhiran)) }}</p>
                            </div>
                        </div>
                        @endif
                        @if ($rekomendasi->pemutakhiran_at !== null && $rekomendasi->pemutakhiran_at !== '' && $rekomendasi->pemutakhiran_by !== '')
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto d-none d-md-block" id="limiter">:</div>
                            <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                <p>Dimutakhirkan oleh {{ $rekomendasi->pemutakhiran_by }} pada {{ \Carbon\Carbon::parse($rekomendasi->pemutakhiran_at )->translatedFormat('d M Y')}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                <!-- akan muncul peringatan untuk mengunggah bukti input SIPTL terlebih dahulu -->
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">Peringatan!</h4>
                    <p>Anda harus mengunggah bukti input SIPTL BPK terlebih dahulu sebelum melakukan pemutakhiran status rekomendasi.</p>
                    <hr>
                    <p class="mb-0">Silakan unggah bukti input SIPTL BPK terlebih dahulu.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

<!-- modal upload bukti input SIPTL -->
<div class="modal fade text-left" id="siptlModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Bukti Input SIPTL</h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="/pemutakhiran-status/{{ $rekomendasi->id }}/siptl" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group mandatory">
                        <label for="bukti_input_siptl" class="form-label">Bukti Input SIPTL</label>
                        <input type="file" class="basic-filepond" name="bukti_input_siptl" required>
                        @error('bukti_input_siptl')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mandatory">
                        <label for="detail_bukti_input_siptl" class="form-label">Detail Bukti Input SIPTL</label>
                        <div class="card-body">
                            <textarea class="form-control" name="detail_bukti_input_siptl" id="detail_bukti_input_siptl" rows="5" required></textarea>
                            @error('detail_bukti_input_siptl')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light ms-1" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    @if ($rekomendasi->buktiInputSIPTL->bukti_input_siptl === 'Belum Diunggah!')
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

<!-- modal pemutakhiran status -->
<div class="modal fade text-left" id="pemutakhiranModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Pemutakhiran Status Rekomendasi</h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="/pemutakhiran-status/{{ $rekomendasi->id }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="form-group mandatory">
                        <label for="status_rekomendasi" class="form-label">Status Rekomendasi</label>
                        <select class="form-select" name="status_rekomendasi" id="status_rekomendasi" required>
                            <option value="Sesuai" {{ $rekomendasi->status_rekomendasi === 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                            <option value="Belum Sesuai" {{ $rekomendasi->status_rekomendasi === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                            <option value="Belum Ditindaklanjuti" {{ $rekomendasi->status_rekomendasi === 'Belum Ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                            <option value="Tidak Ditindaklanjuti" {{ $rekomendasi->status_rekomendasi === 'Tidak Ditindaklanjuti' ? 'selected' : '' }}>Tidak Ditindaklanjuti</option>
                        </select>
                    </div>
                    <div class="form-group mandatory" id="catatan_pemutakhiran_group" style="display: none;">
                        <label for="catatan_pemutakhiran" class="form-label">Catatan Pemutakhiran</label>
                        <div class="card-body">
                            <textarea class="form-control" name="catatan_pemutakhiran" id="catatan_pemutakhiran" rows="5" required>{{ old('catatan_pemutakhiran', $rekomendasi->catatan_pemutakhiran ?? '') }}</textarea>
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
    $(document).ready(function () {
        $('#status_rekomendasi').change(function () {
            if ($(this).val() === 'Sesuai') {
                $('#catatan_pemutakhiran_group').hide();
                $('#catatan_pemutakhiran').prop('required', false); // Catatan tidak wajib diisi
            } else {
                $('#catatan_pemutakhiran_group').show();
                $('#catatan_pemutakhiran').prop('required', true); // Catatan wajib diisi
            }
        });
    });
</script>


<script>
    // Ambil tombol "Upload Bukti Input SIPTL"
    var uploadBtn = document.getElementById('uploadBtn');

    // Tambahkan event listener untuk menampilkan modal saat tombol diklik
    uploadBtn.addEventListener('click', function() {
        var uploadModal = new bootstrap.Modal(document.getElementById('siptlModal'));
        uploadModal.show();
    });

    // Ambil tombol "Pemutakhiran Status"
    var pemutakhiranBtn = document.getElementById('pemutakhiranBtn');

    // Tambahkan event listener untuk menampilkan modal saat tombol diklik
    pemutakhiranBtn.addEventListener('click', function() {
        var pemutakhiranModal = new bootstrap.Modal(document.getElementById('pemutakhiranModal'));
        pemutakhiranModal.show();
    });

</script>

<script>
    $(document).ready(function() {
        // Fungsi untuk menyembunyikan semua tombol status
        function hideAllButtons() {
            $('#btnStatusBuktiInput').hide();
        }

        // Pemanggilan fungsi hideAllButtons() saat halaman dimuat
        hideAllButtons();

        // Fungsi untuk menampilkan tombol status bukti input SIPTL
        function showBuktiInputButton() {
            $('#btnStatusBuktiInput').show();
            $('#btnStatusPemutakhiran').hide();
        }

        // Fungsi untuk menampilkan tombol status pemutakhiran
        function showPemutakhiranButton() {
            $('#btnStatusPemutakhiran').show();
        }

        // Memanggil fungsi yang sesuai berdasarkan tab yang aktif
        $('.nav-link').on('shown.bs.tab', function (e) {
            var activeTabId = $(e.target).attr('aria-controls');

            if (activeTabId === 'bukti_input_siptl') {
                hideAllButtons();
                $('#btnStatusPemutakhiran').hide();
                showBuktiInputButton();
            } else if (activeTabId === 'pemutakhiran_status') {
                hideAllButtons();
                showPemutakhiranButton();
            } else {
                hideAllButtons();
                $('#btnStatusPemutakhiran').hide();
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

<script>
document.getElementById('deleteButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                    Swal.fire(
                        'Berhasil!',
                        'Data telah berhasil dihapus.',
                        'success'
                    );
                }
            });
        });
</script>

<script>
    new DataTable('#table1', {
        info: true,
        ordering: true,
        paging: true,
        searching: true,
        lengthChange: true,
        lengthMenu: [5, 10, 25, 50, 75, 100],
        destroy: true,

        // bahasa indonesia
        language: {
            "info": "<sup><big>dari _TOTAL_ entri</big></sup>",
            "infoEmpty": "<sup><big>0 entri</big></sup>",
            "infoFiltered": "<sup><big>(filter dari _MAX_ total entri)</big></sup>",
            "lengthMenu": "_MENU_ &nbsp;",
            "search": "<i class='bi bi-search'></i>  ",
            "zeroRecords": "Tidak ada data yang cocok",
            "paginate": {
                "next": "<i class='bi bi-chevron-right'></i>",
                "previous": "<i class='bi bi-chevron-left'></i>"
            }
        },

        dom: '<"d-flex justify-content-between mb-4"fB>rt<"d-flex justify-content-between mt-4"<"d-flex justify-content-start"li><"col-md-6"p>>',
    });
</script>

@endsection

