@extends('layouts.horizontal')

@section('style')

<link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond/filepond.css') }}">

<style>

/* CSS untuk konten modal */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* Atur margin atas dan bawah dan kiri-kanan secara otomatis */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Sesuaikan lebar modal sesuai kebutuhan */
    border-radius: 10px; /* Tambahkan sudut bulat pada modal */
}

.status-badge {
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
    }

    .status-proses {
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
        </div>
        <div class="col-auto d-flex ms-auto">
            <button class="btn btn-primary" id="uploadBtn">
                @if (($tindak_lanjut->dokumen_tindak_lanjut === null || $tindak_lanjut->dokumen_tindak_lanjut === 'Belum Diupload!'))
                    <i class="bi bi-plus"></i>
                    Dokumen Tindak Lanjut
                @else
                    <i class="bi bi-pencil"></i>
                    &nbsp;Dokumen Tindak Lanjut
                @endif
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><b>A. Detail Pemeriksaan</b></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Pemeriksaan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->pemeriksaan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Tahun</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->tahun_pemeriksaan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Jenis Pemeriksaan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->jenis_pemeriksaan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Hasil Pemeriksaan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->hasil_pemeriksaan }}</p>
                </div>
            </div>
        </div>

        <div class="card-header">
            <h4 class="card-title"><b>B. Detail Rekomendasi</b></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Jenis Temuan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->jenis_temuan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Uraian Temuan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->uraian_temuan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Rekomendasi</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->rekomendasi }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Catatan Rekomendasi</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->catatan_rekomendasi }}</p>
                </div>
            </div>
        </div>

        <div class="card-header">
            <h4 class="card-title"><b>C. Tindak Lanjut</b></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Tindak Lanjut</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $tindak_lanjut->tindak_lanjut }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Unit Kerja</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $tindak_lanjut->unit_kerja }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Tim Pemantauan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $tindak_lanjut->tim_pemantauan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Tenggat Waktu</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $tindak_lanjut->tenggat_waktu }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Dokumen Tindak Lanjut</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $tindak_lanjut->dokumen_tindak_lanjut }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Detail Dokumen Tindak Lanjut</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $tindak_lanjut->detail_dokumen_tindak_lanjut }}</p>
                </div>
            </div>
        </div>

        <div class="card-header">
            <h4 class="card-title"><b>D. Hasil Pemeriksaan</b></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Status Tindak Lanjut</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p><span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }}">{{ $tindak_lanjut->status_tindak_lanjut }}</span></p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Catatan Tindak Lanjut</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $tindak_lanjut->catatan_tindak_lanjut }}</p>
                </div>
            </div>
    </div>
</section>

@endsection


<!-- modal upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Dokumen Tindak Lanjut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/kelola-tindak-lanjut/{{ $tindak_lanjut->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-body
                ">
                <!-- input dengan tipe hidden untuk data yang sbeelumnya -->
                <input type="hidden" name="tindak_lanjut" value="{{ $tindak_lanjut->tindak_lanjut }}">
                <input type="hidden" name="unit_kerja" value="{{ $tindak_lanjut->unit_kerja }}">
                <input type="hidden" name="tim_pemantauan" value="{{ $tindak_lanjut->tim_pemantauan }}">
                <input type="hidden" name="tenggat_waktu" value="{{ $tindak_lanjut->tenggat_waktu }}">
                <input type="hidden" name="rekomendasi_id" value="{{ $tindak_lanjut->rekomendasi_id }}">

                <div class="mb-3">
                    <label for="dokumen_tindak_lanjut" class="form-label">Dokumen Tindak Lanjut</label>
                    <input type="file" class="with-validation-filepond" required multiple
                            data-max-file-size="1MB" data-max-files="3" name="dokumen_tindak_lanjut">
                </div>
                <div class="mb-3">
                    <label for="dokumen_tindak_lanjut" class="form-label">Detail Dokumen Tindak Lanjut</label>
                    <div class="card-body">
                        <textarea class="form-control" name="detail_dokumen_tindak_lanjut" id="detail_dokumen_tindak_lanjut" rows="3" required></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-auto ms-auto">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


@section('script')

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
        case 'Proses':
            return 'status-proses';
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
