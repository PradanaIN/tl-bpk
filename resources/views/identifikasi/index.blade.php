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

    .status-belum-sesuai {
        background-color: #FFD700; /* Kuning */
        color: #000000; /* Hitam */
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
    $loggedInUserRole = auth()->user()->role; // Dapatkan peran pengguna yang sedang login
    $no = 1;


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
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tindak Lanjut</th>
                            <th>Unit Kerja</th>
                            <th>Tenggat Waktu</th>
                            <th>Hasil Identifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tindak_lanjut as $tindak_lanjut)
                        @if ($loggedInUserRole == 'Super Admin' || $loggedInUserRole == 'Tim Pemantauan Wilayah I' && $tindak_lanjut->tim_pemantauan == 'Tim Pemantauan Wilayah I' || $loggedInUserRole == 'Tim Pemantauan Wilayah II' && $tindak_lanjut->tim_pemantauan == 'Tim Pemantauan Wilayah II' || $loggedInUserRole == 'Tim Pemantauan Wilayah III' && $tindak_lanjut->tim_pemantauan == 'Tim Pemantauan Wilayah III')
                            <tr class="clickable-row" data-href="/identifikasi/{{ $tindak_lanjut->id }}">
                                <td>{{ $no++ }}</td>
                                <td>{{ implode(' ', array_slice(str_word_count(strip_tags($tindak_lanjut->tindak_lanjut), 1), 0, 10)) }}{{ str_word_count(strip_tags($tindak_lanjut->tindak_lanjut)) > 10 ? '...' : '' }}</td>
                                <td>{{ $tindak_lanjut->unit_kerja }}</td>
                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu )->translatedFormat('d M Y') }}</td>
                                <td style="text-align: center;">
                                    <span class="status-badge {{ getStatusClass($tindak_lanjut->status_tindak_lanjut) }}">{{ $tindak_lanjut->status_tindak_lanjut }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-around align-items-center">
                                        <a href="/identifikasi/{{ $tindak_lanjut->id }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Tindak Lanjut">
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

            dom: '<"d-flex justify-content-between mb-4"fB>rt<"d-flex justify-content-between mt-4"<"d-flex justify-content-start"li><"col-md-6"p>>'
        });
    </script>
@endsection
