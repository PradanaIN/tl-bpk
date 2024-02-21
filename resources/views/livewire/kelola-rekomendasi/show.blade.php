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
        </div>
        <div class="col-auto d-flex ms-auto">
            <div class="col-auto">
                <a href="/kelola-rekomendasi/{{ $rekomendasi->id }}/edit" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Rekomendasi">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="/kelola-rekomendasi/{{ $rekomendasi->id }}" method="post" class="d-inline" id="deleteForm">
                    @method('delete')
                    @csrf
                    <button class="btn btn-danger" type="button" id="deleteButton" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Rekomendasi">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
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
            <div class="table-responsive" style="border: 1px solid black; padding: 10px;">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tindak Lanjut</th>
                            <th>Unit Kerja</th>
                            <th>Tim Pemantauan</th>
                            <th>Tenggat Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekomendasi->tindakLanjut as $index => $tindakLanjut)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tindakLanjut->tindak_lanjut }}</td>
                                <td>{{ $tindakLanjut->unit_kerja }}</td>
                                <td>{{ $tindakLanjut->tim_pemantauan }}</td>
                                <td>{{ $tindakLanjut->tenggat_waktu }}</td>
                                <td>
                                    <span class="status-badge {{ getStatusClass($tindakLanjut->status_tindak_lanjut) }}">{{ $tindakLanjut->status_tindak_lanjut }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
