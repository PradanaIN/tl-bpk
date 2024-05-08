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

@php
    $loggedInUserRole = auth()->user()->role; // Dapatkan peran pengguna yang sedang login
    $loggedInUserUnitKerja = auth()->user()->unit_kerja; // Dapatkan unit kerja pengguna yang sedang login
    $no = 1;
@endphp

@section('section')

<section class="row">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rekomendasi</th>
                            <th>Tindak Lanjut</th>
                            <th>Tenggat Waktu</th>
                            <th>Status Tindak Lanjut</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tindak_lanjut as $tindak_lanjut)
                            @if ($loggedInUserRole == 'Super Admin' || $loggedInUserRole == 'Tim Koordinator' || $tindak_lanjut->unit_kerja == $loggedInUserUnitKerja)
                                <tr class="clickable-row" data-href="/tindak-lanjut/{{ $tindak_lanjut->id }}">
                                    <td>{{ $no++ }}</td>
                                    <td>{!! $tindak_lanjut->rekomendasi->rekomendasi !!}</td>
                                    <td>{!! $tindak_lanjut->tindak_lanjut !!}</td>
                                    <td>{{ \Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu )->translatedFormat('d M Y')}}</td>
                                    {{-- @if ($tindak_lanjut->bukti_tindak_lanjut === null || $tindak_lanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                                        <td style="text-align:center;"><span class="status-badge bg-warning text-black">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span></td>
                                    @else
                                        <td style="text-align:center;"><span class="status-badge bg-success text-white">{{ $tindak_lanjut->bukti_tindak_lanjut }}</span></td>
                                    @endif --}}
                                    <td style="text-align:center;">
                                        <span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }}">{{ $tindak_lanjut->status_tindak_lanjut }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around align-items-center">
                                            <a href="/tindak-lanjut/{{ $tindak_lanjut->id }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Tindak Lanjut">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
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
        document.addEventListener("DOMContentLoaded", function() {
            var rows = document.querySelectorAll('.clickable-row');

            rows.forEach(function(row) {
                row.addEventListener('click', function() {
                    var href = row.dataset.href;
                    if (href) {
                        window.location.href = href;
                    }
                });
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
            // buttons: [
            //     {
            //         text: '<i class="bi bi-plus"></i> Tambah Rekomendasi',
            //         className: 'btn btn-primary',
            //         action: function ( e, dt, node, config ) {
            //             window.location.href = '/rekomendasi/create';
            //         }
            //     }
            // ]
        });
    </script>
@endsection

@php
function getStatusClass($status) {
    switch ($status) {
        case 'Proses':
            return 'status-proses';
            break;
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
