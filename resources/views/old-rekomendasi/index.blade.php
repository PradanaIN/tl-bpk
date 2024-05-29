@extends('layouts.horizontal')

@php
    function getStatusClass($status)
    {
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
                <div class="row">
                    <div class="col-md-6">
                        <label for="filterSemester" class="form-label">Filter Semester Rekomendasi:</label>
                        <select class="form-select" id="filterSemester">
                            <option value="all">Semua Semester</option>
                            @foreach ($semesterRekomendasi as $semester)
                                <option value="{{ $semester }}">{{ $semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="filterStatus" class="form-label">Filter Status Rekomendasi:</label>
                        <select class="form-select" id="filterStatus">
                            <option value="all">Semua Status</option>
                            <option value="Sesuai">Sesuai</option>
                            <option value="Belum Sesuai">Belum Sesuai</option>
                            <option value="Belum Ditindaklanjuti">Belum Ditindaklanjuti</option>
                            <option value="Tidak Ditindaklanjuti">Tidak Ditindaklanjuti</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
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
                                <th>Semester</th>
                                <th>Status Rekomendasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rekomendasi as $rekomendasi)
                                <tr class='clickable-row' data-href="/old-rekomendasi/{{ $rekomendasi->id }}}}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $rekomendasi->tahun_pemeriksaan }}</td>
                                    <td>{{ $rekomendasi->pemeriksaan }}</td>
                                    <td>{{ implode(' ', array_slice(str_word_count(strip_tags(html_entity_decode($rekomendasi->rekomendasi)), 1), 0, 10)) }}{{ str_word_count(strip_tags(html_entity_decode($rekomendasi->rekomendasi))) > 10 ? '...' : '' }}
                                    </td>
                                    <td class="text-center">{{ $rekomendasi->semester_rekomendasi }}</td>
                                    <td class="text-center">
                                        <span
                                            class="status-badge {{ getStatusClass($rekomendasi->status_rekomendasi) }}">{{ $rekomendasi->status_rekomendasi }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around align-items-center">
                                            <a href="/old-rekomendasi/{{ $rekomendasi->id }}" class="btn btn-light"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Rekomendasi">
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
    <!-- filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSemester = document.getElementById('filterSemester');
            const filterStatus = document.getElementById('filterStatus');
            const table = new DataTable('#table1', {
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
                buttons: [{
                    text: '<i class="bi bi-plus"></i><span class="d-none d-md-inline"> Tambah Rekomendasi<span>',
                    className: 'btn btn-primary',
                    action: function(e, dt, node, config) {
                        window.location.href = '/old-rekomendasi/create';
                    }
                }]
            });

            filterSemester.addEventListener('change', function() {
                filterTable();
            });

            filterStatus.addEventListener('change', function() {
                filterTable();
            });

            function filterTable() {
                const selectedSemester = filterSemester.value;
                const selectedStatus = filterStatus.value;

                table.search(selectedSemester).draw();
                table.search(selectedStatus).draw();

                if (selectedSemester === 'all' && selectedStatus === 'all') {
                    table.search('').draw();
                }

                if (selectedSemester === 'all' && selectedStatus !== 'all') {
                    table.search(selectedStatus).draw();
                }

                if (selectedSemester !== 'all' && selectedStatus === 'all') {
                    table.search(selectedSemester).draw();
                }

                if (selectedSemester !== 'all' && selectedStatus !== 'all') {
                    table.search(selectedSemester + ' ' + selectedStatus).draw();
                }
            }
        });
    </script>
    <!-- tooltip -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);
    </script>
    <!-- click row -->
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
    <!-- sweetalert -->
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
