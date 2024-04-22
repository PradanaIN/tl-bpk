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
            <a href="/kelola-rekomendasi" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
            <a href="/kelola-rekomendasi/{{ $rekomendasi->id }}/export" class="btn btn-success ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Export Rekomendasi">
                <i class="bi bi-file-earmark-excel"></i>
                Export Rekomendasi
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
            @if (($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!'))
            <button class="btn btn-warning" id="btnStatus">
                <i class="bi bi-exclamation-triangle"></i>
                &nbsp;Bukti Belum Diunggah!
            </button>
            @else
            <button class="btn btn-success" id="btnStatus" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->format('H:i, d M Y') }}">
                <i class="bi bi-check-square"></i>
                &nbsp;Bukti Diunggah {{ \Carbon\Carbon::parse($tindak_lanjut->upload_at)->diffForHumans() }}
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
                                        <th>Dokumen</th>
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
                                            <td style="text-align:center;">{{ \Carbon\Carbon::parse($tindakLanjut->tenggat_waktu )->format(' d F Y') }}</td>
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

