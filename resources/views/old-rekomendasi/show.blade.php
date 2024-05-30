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
        <div class="row mb-3 flex-wrap">
            <div class="col-auto d-flex me-auto">
                <a href="/old-rekomendasi" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i>
                    <span class="d-none d-md-inline">Kembali</span>
                </a>
                <a href="/old-rekomendasi/{{ $rekomendasi->id }}/export" class="btn btn-success ms-2" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Export Rekomendasi" target="_blank">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span class="d-none d-md-inline">Export Rekomendasi</span>
                </a>
            </div>
            @canany(['Tim Koordinator', 'Super Admin', 'Admin'])
            <div class="col-auto d-flex ms-auto">
                <div class="col-auto">
                    <a href="/old-rekomendasi/{{ $rekomendasi->id }}/edit" class="btn btn-light" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Edit Rekomendasi">
                        <i class="bi bi-pencil"></i>
                        <span class="d-none d-md-inline">&nbsp;Ubah</span>
                    </a>
                    <form action="/old-rekomendasi/{{ $rekomendasi->id }}" method="post" class="d-inline" id="deleteForm">
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger" type="button" id="deleteButton" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Hapus Rekomendasi">
                            <i class="bi bi-trash"></i>
                            <span class="d-none d-md-inline">&nbsp;Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
            @endcanany
        </div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pemeriksaan-tab" data-bs-toggle="tab" href="#pemeriksaan"
                            role="tab" aria-controls="pemeriksaan" aria-selected="true">
                            <h6>Pemeriksaan</h6>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="rekomendasi-tab" data-bs-toggle="tab" href="#rekomendasi" role="tab"
                            aria-controls="rekomendasi" aria-selected="false">
                            <h6>Rekomendasi</h6>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="lhp-tab" data-bs-toggle="tab" href="#lhp" role="tab"
                            aria-controls="lhp" aria-selected="false">
                            <h6>Dokumen LHP</h6>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tindaklanjut-tab" data-bs-toggle="tab" href="#tindaklanjut" role="tab"
                            aria-controls="tindaklanjut" aria-selected="false">
                            <h6>Tindak Lanjut</h6>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="bukti_input_siptl-tab" data-bs-toggle="tab" href="#bukti_input_siptl"
                            role="tab" aria-controls="bukti_input_siptl" aria-selected="false">
                            <h6>Bukti Input SIPTL</h6>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pemutakhiran_status-tab" data-bs-toggle="tab" href="#pemutakhiran_status"
                            role="tab" aria-controls="pemutakhiran_status" aria-selected="false">
                            <h6>Pemutakhiran Status</h6>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="pemeriksaan" role="tabpanel"
                        aria-labelledby="pemeriksaan-tab">
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Pemeriksaan</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ $rekomendasi->pemeriksaan }}</p>
                                </div>
                            </div>
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Tahun</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ $rekomendasi->tahun_pemeriksaan }}</p>
                                </div>
                            </div>
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Jenis Pemeriksaan</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ $rekomendasi->jenis_pemeriksaan }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Hasil Pemeriksaan</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ strip_tags(html_entity_decode($rekomendasi->hasil_pemeriksaan)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="rekomendasi" role="tabpanel" aria-labelledby="rekomendasi-tab">
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Jenis Temuan</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ $rekomendasi->jenis_temuan }}</p>
                                </div>
                            </div>
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Uraian Temuan</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ strip_tags(html_entity_decode($rekomendasi->uraian_temuan)) }}</p>
                                </div>
                            </div>
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Rekomendasi</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ strip_tags(html_entity_decode($rekomendasi->rekomendasi)) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Catatan Rekomendasi</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ strip_tags(html_entity_decode($rekomendasi->catatan_rekomendasi)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="lhp" role="tabpanel" aria-labelledby="lhp-tab">
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Laporan Hasil Pemeriksaan</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <div class="col-auto d-flex ms-auto">
                                        <span
                                            class="status-badge bg-success text-white me-2">{{ $rekomendasi->lhp }}</span>
                                        <div class="col-auto d-flex align-content-center">
                                            <a href="{{ asset('storage/uploads/lhp/' . $rekomendasi->lhp) }}"
                                                class="btn btn-secondary me-2" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Unduh LHP" target="_blank">
                                                <i class="bi bi-download"></i>
                                                <span class="d-none d-md-inline">&nbsp;Unduh LHP</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tindaklanjut" role="tabpanel" aria-labelledby="tindaklanjut-tab">
                        <div class="card-body">
                            <div class="table-responsive-md">
                                <table class="table" id="table1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tindak Lanjut</th>
                                            <th>Unit Kerja</th>
                                            <th>Tim Pemantauan</th>
                                            <th>Tenggat Waktu</th>
                                            <th>Bukti Tindak Lanjut</th>
                                            <th>Status Tindak Lanjut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rekomendasi->tindakLanjut as $index => $tindakLanjut)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ strip_tags(html_entity_decode($tindakLanjut->tindak_lanjut)) }}</td>
                                                <td>{{ $tindakLanjut->unit_kerja }}</td>
                                                <td>{{ $tindakLanjut->tim_pemantauan }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($tindakLanjut->tenggat_waktu)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($tindakLanjut->bukti_tindak_lanjut === null || $tindakLanjut->bukti_tindak_lanjut === 'Belum Diunggah!')
                                                        <span class="status-badge status-belum-sesuai">Belum
                                                            Diunggah!</span>
                                                    @else
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span
                                                                class="status-badge status-sesuai mb-1">{{ Str::limit($tindakLanjut->bukti_tindak_lanjut, 10) }}...</span>
                                                            <a href="{{ asset('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut) }}"
                                                                class="btn btn-secondary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom" title="Download Bukti" target="_blank">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;">
                                                    <span
                                                        class="status-badge {{ getStatusClass($tindakLanjut->status_tindak_lanjut) }}">{{ $tindakLanjut->status_tindak_lanjut }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bukti_input_siptl" role="tabpanel"
                        aria-labelledby="bukti_input_siptl-tab">
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Bukti Input SIPTL</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <div class="col-auto d-flex ms-auto">
                                        <span
                                            class="status-badge bg-success text-white me-2">{{ $rekomendasi->buktiInputSIPTL->bukti_input_siptl }}</span>
                                        @canany(['Tim Koordinator', 'Super Admin'])
                                            <div class="col-auto d-flex align-content-center">
                                                <a href="{{ asset('uploads/bukti_input_siptl/' . $rekomendasi->buktiInputSIPTL->bukti_input_siptl) }}"
                                                    class="btn btn-secondary me-2" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="Unduh Bukti Input SIPTL" target="_blank">
                                                    <i class="bi bi-download"></i>
                                                    <span class="d-none d-md-inline">&nbsp;Unduh Bukti</span>
                                                </a>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Detail Input SIPTL</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ strip_tags(html_entity_decode($rekomendasi->buktiInputSIPTL->detail_bukti_input_siptl)) }}
                                    </p>
                                </div>
                            </div>
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Informasi Lainnya</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>Diunggah oleh {{ $rekomendasi->buktiInputSIPTL->upload_by }} pada
                                        {{ \Carbon\Carbon::parse($rekomendasi->buktiInputSIPTL->upload_at)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pemutakhiran_status" role="tabpanel"
                        aria-labelledby="pemutakhiran_status-tab">
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Status Rekomendasi</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <div class="col-auto d-flex align-items-center">
                                        <span
                                            class="status-badge {{ getStatusClass($rekomendasi->status_rekomendasi) }} me-2">{{ $rekomendasi->status_rekomendasi }}</span>
                                    </div>
                                </div>
                            </div>
                            @if ($rekomendasi->catatan_pemutakhiran !== '' && $rekomendasi->catatan_pemutakhiran !== null)
                                <div class="row custom-row">
                                    <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                        <p class="fw-bold">Catatan Pemutakhiran</p>
                                    </div>
                                    <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                    <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                        <p>{{ strip_tags(html_entity_decode($rekomendasi->catatan_pemutakhiran)) }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="row custom-row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Semester Pemutakhiran</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>{{ $rekomendasi->semester_pemutakhiran }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-auto" id="judul">
                                    <p class="fw-bold">Informasi Lainnya</p>
                                </div>
                                <div class="col-auto d-none d-md-block" id="limiter">:</div>
                                <div class="col-lg-8 col-md-9 col-sm-12" id="text">
                                    <p>Dimutakhirkan oleh {{ $rekomendasi->pemutakhiran_by }} pada
                                        {{ \Carbon\Carbon::parse($rekomendasi->pemutakhiran_at)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
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
