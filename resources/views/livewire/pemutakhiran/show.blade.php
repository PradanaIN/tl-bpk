@extends('layouts.horizontal')

@section('style')
<style>
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
            <a href="/pemutakhiran-status" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
            <a href="/kelola-rekomendasi/{{ $rekomendasi->id }}/export" class="btn btn-success ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Export Rekomendasi">
                <i class="bi bi-file-earmark-excel"></i>
                Export Rekomendasi
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
            @if (($rekomendasi->bukti_input_siptl === null || $rekomendasi->bukti_input_siptl === 'Belum Diunggah!'))
            <button class="btn btn-warning" id="btnStatusBuktiInput">
                <i class="bi bi-exclamation-triangle"></i>
                &nbsp;Bukti Belum Diunggah!
            </button>
            @else
            <button class="btn btn-success" id="btnStatusBuktiInput" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($rekomendasi->upload_at)->translatedFormat('H:i, d M Y') }}">
                <i class="bi bi-check-square"></i>
                &nbsp;Bukti Diunggah {{ \Carbon\Carbon::parse($rekomendasi->upload_at)->diffForHumans() }}
            </button>
            @endif
            @if (($rekomendasi->status_rekomendasi === 'Proses' ))
            <button class="btn btn-warning" id="btnStatusPemutakhiran">
                <i class="bi bi-exclamation-triangle"></i>
                &nbsp;Rekomendasi Belum Dimutakhirkan!
            </button>
            @else
            <button class="btn btn-success" id="btnStatusPemutakhiran" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($rekomendasi->pemutakhiran_at)->translatedFormat('H:i, d M Y') }}">
                <i class="bi bi-check-square"></i>
                &nbsp;Dimutakhirkan {{ \Carbon\Carbon::parse($rekomendasi->pemutakhiran_at)->diffForHumans() }}
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
                                <p>{!! $rekomendasi->hasil_pemeriksaan !!}</p>
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
                <div class="tab-pane fade" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                    {{-- <div class="card-header">
                        <h4 class="card-title"><b>Detail Tindak Lanjut</b></h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="table1">
                                <thead>
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
                                            <td >{!! $tindakLanjut->tindak_lanjut !!}</td>
                                            <td >{{ $tindakLanjut->unit_kerja }}</td>
                                            <td>{{ $tindakLanjut->tim_pemantauan }}</td>
                                            <td style="text-align:center;">{{ \Carbon\Carbon::parse($tindakLanjut->tenggat_waktu )->translatedFormat('d M Y') }}</td>
                                            <td style="text-align:center;">
                                                @if ($tindakLanjut->bukti_tindak_lanjut === null || $tindakLanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                                                    <span class="badge bg-danger">Belum Diunggah!</span>
                                                @else
                                                <a href="{{ asset('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut) }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download Bukti">
                                                    <i class="bi bi-download"></i>
                                                </a>
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
                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Bukti Input SIPTL</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                @if ($rekomendasi->bukti_input_siptl === null || $rekomendasi->bukti_input_siptl === 'Belum Diunggah!')
                                    <span class="badge bg-danger">Belum Diunggah!</span>
                                @else
                                    <a href="{{ asset('uploads/bukti_input_siptl/' . $rekomendasi->bukti_input_siptl) }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download Bukti">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Tanggal Input SIPTL</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{{ \Carbon\Carbon::parse($rekomendasi->tanggal_input_siptl)->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Catatan Input SIPTL</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $rekomendasi->catatan_bukti_input_siptl !!}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-2">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $rekomendasi->upload_by !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="pemutakhiran_status" role="tabpanel" aria-labelledby="pemutakhiran_status-tab">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <p class="fw-bold">Status Rekomendasi</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p><span class="status-badge {{ getStatusClass($rekomendasi->status_rekomendasi) }}">{{ $rekomendasi->status_rekomendasi }}</span></p>
                            </div>
                            <div class="col-auto d-flex ms-auto">
                                    @if (($rekomendasi->status_rekomendasi === null || $rekomendasi->status_rekomendasi === 'Proses'))
                                    <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tambah Status Pemutakhiran">
                                        <i class="bi bi-plus"></i>
                                        &nbsp;Tambah Pemutakhiran
                                    </button>
                                    @else
                                    <button class="btn btn-primary" id="uploadBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Status Pemutakhiran">
                                        <i class="bi bi-pencil"></i>
                                        &nbsp;Ubah Pemutakhiran
                                    </button>
                                    @endif
                                </button>
                            </div>
                        </div>
                        @if ($rekomendasi->catatan_pemutakhiran !== '' && $rekomendasi->catatan_pemutakhiran !== null)
                        <div class="row">
                            <div class="col-3">
                                <p class="fw-bold">Catatan Pemutakhiran</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>{!! $rekomendasi->catatan_pemutakhiran !!}</p>
                            </div>
                        </div>
                        @endif
                        @if ($rekomendasi->status_rekomendasi !== 'Proses')
                        <div class="row">
                            <div class="col-3">
                                <p class="fw-bold">Informasi Lainnya</p>
                            </div>
                            <div class="col-auto">:</div>
                            <div class="col">
                                <p>Dimutakhirkan oleh {{ $rekomendasi->pemutakhiran_by }} pada {{ \Carbon\Carbon::parse($rekomendasi->pemutakhiran_at )->translatedFormat('d M Y')}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
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
                            <option value="Proses" {{ $rekomendasi->status_rekomendasi === 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Sesuai" {{ $rekomendasi->status_rekomendasi === 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                            <option value="Belum Sesuai" {{ $rekomendasi->status_rekomendasi === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                            <option value="Belum Ditindaklanjuti" {{ $rekomendasi->status_rekomendasi === 'Belum Ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                            <option value="Tidak Ditindaklanjuti" {{ $rekomendasi->status_rekomendasi === 'Tidak Ditindaklanjuti' ? 'selected' : '' }}>Tidak Ditindaklanjuti</option>
                        </select>
                    </div>
                    <div class="form-group mandatory" id="catatan_pemutakhiran_group" style="display: none;">
                        <label for="catatan_pemutakhiran" class="form-label">Catatan Pemutakhiran</label>
                        <div class="card-body">
                            <textarea class="form-control" name="catatan_pemutakhiran" id="catatan_pemutakhiran" rows="5" required></textarea>
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
        $('#status_rekomendasi').change(function () {
            if ($(this).val() === 'Sesuai' || $(this).val() === 'Proses') {
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
    // Ambil tombol "Upload Dokumen TL"
    var uploadBtn = document.getElementById('uploadBtn');

    // Tambahkan event listener untuk menampilkan modal saat tombol diklik
    uploadBtn.addEventListener('click', function() {
        var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        uploadModal.show();
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

@endsection

