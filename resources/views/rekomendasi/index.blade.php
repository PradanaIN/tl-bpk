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
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Pemeriksaan</th>
                            <th>Rekomendasi</th>
                            <th>Status Rekomendasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekomendasi as $rekomendasi)
                        <tr class='clickable-row' data-href="/rekomendasi/{{ $rekomendasi->id }}}}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rekomendasi->tahun_pemeriksaan }}</td>
                            <td>{{ $rekomendasi->pemeriksaan }}</td>
                            <td>{{ strip_tags(html_entity_decode($rekomendasi->rekomendasi)) }}</td>
                            <td style="text-align:center;">
                                <span class="status-badge {{ getStatusClass($rekomendasi->status_rekomendasi) }}">{{ $rekomendasi->status_rekomendasi }}</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-around align-items-center">
                                    <a href="/rekomendasi/{{ $rekomendasi->id }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Rekomendasi">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        var tableRows = document.querySelectorAll('.clickable-row');

        function applyFilter() {
            var tahun = document.getElementById('tahun').value.toLowerCase();
            var jenisPemeriksaan = document.getElementById('jenisPemeriksaan').value.toLowerCase();
            var unitKerja = document.getElementById('unitKerja').value.toLowerCase();
            var statusRekomendasi = document.getElementById('statusRekomendasi').value.toLowerCase();

            tableRows.forEach(function(row) {
                var rowTahun = row.cells[1].textContent.toLowerCase();
                var rowJenisPemeriksaan = row.cells[2].textContent.toLowerCase();
                var rowUnitKerja = row.cells[3].textContent.toLowerCase();
                var rowStatusRekomendasi = row.cells[4].textContent.toLowerCase();

                var isFiltered =
                    (tahun === '' || rowTahun.includes(tahun)) &&
                    (jenisPemeriksaan === '' || rowJenisPemeriksaan.includes(jenisPemeriksaan)) &&
                    (unitKerja === '' || rowUnitKerja.includes(unitKerja)) &&
                    (statusRekomendasi === '' || rowStatusRekomendasi.includes(statusRekomendasi));

                row.style.display = isFiltered ? '' : 'none';
            });
        }

        document.getElementById('applyFilter').addEventListener('click', function() {
            applyFilter();
        });

        document.getElementById('resetFilter').addEventListener('click', function() {
            document.getElementById('tahun').value = '';
            document.getElementById('jenisPemeriksaan').value = '';
            document.getElementById('unitKerja').value = '';
            document.getElementById('statusRekomendasi').value = '';
            applyFilter();
        });
    });
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
            buttons: [
                {
                    text: '<i class="bi bi-plus"></i><span class="d-none d-md-inline"> Tambah Rekomendasi<span>',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        window.location.href = '/rekomendasi/create';
                    }
                }
            ]
        });
    </script>

    <script>

        @if (session()->has('create'))
            Swal.fire({
                title: 'Success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('create') }}'
            });

        @elseif (session()->has('update'))
            Swal.fire({
                title: 'Success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('update') }}'
            });

        @elseif (session()->has('delete'))
            Swal.fire({
                title: 'Success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('delete') }}'
            });

        @elseif (session()->has('error'))
            Swal.fire({
                title: 'Error',
                icon: 'error',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('error') }}'
            });
        @endif

    </script>
@endsection
