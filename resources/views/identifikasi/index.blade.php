@extends('layouts.horizontal')

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
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="filterSemester" class="form-label">Filter Semester Tindak Lanjut:</label>
                    <select class="form-select" id="filterSemester">
                        <option value="all">Semua Semester</option>
                        @foreach($semesterTindakLanjut as $semester)
                            <option value="{{ $semester }}">{{ $semester }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="filterStatus" class="form-label">Filter Status Tindak Lanjut:</label>
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
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Semester Tindak Lanjut</th>
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
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">{{ $tindak_lanjut->semester_tindak_lanjut }}</td>
                                <td>{{ implode(' ', array_slice(str_word_count(strip_tags($tindak_lanjut->tindak_lanjut), 1), 0, 10)) }}{{ str_word_count(strip_tags($tindak_lanjut->tindak_lanjut)) > 10 ? '...' : '' }}</td>
                                <td>{{ $tindak_lanjut->unit_kerja }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($tindak_lanjut->tenggat_waktu )->translatedFormat('d M Y') }}</td>
                                <td class="text-center">
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
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
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
@endsection
