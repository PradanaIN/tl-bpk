@extends('layouts.horizontal')

@section('section')

<section class="row">
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Pemeriksaan</th>
                            <th>Temuan</th>
                            <th>Rekomendasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekomendasi as $rekomendasi)
                        <tr class='clickable-row' data-href="/kelola-rekomendasi/{{ $rekomendasi->id }}}}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rekomendasi->tahun_pemeriksaan }}</td>
                            <td>{{ $rekomendasi->pemeriksaan }}</td>
                            <td>{{ $rekomendasi->jenis_temuan }}</td>
                            <td>{{ $rekomendasi->rekomendasi }}</td>
                            <td>
                                <div class="d-flex justify-content-around align-items-center">
                                    <a href="/kelola-rekomendasi/{{ $rekomendasi->id }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Rekomendasi">
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
                    text: '<i class="bi bi-plus"></i> Tambah Rekomendasi',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        window.location.href = '/kelola-rekomendasi/create';
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
