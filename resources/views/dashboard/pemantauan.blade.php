@extends('layouts.horizontal')

@section('style')
<style>
.stats-icon i {
    font-size: 16px; /* Ubah ukuran ikon sesuai kebutuhan */
}

caption {
        caption-side: top;
        text-align: left;
        padding-bottom: 10px;
        font-weight: bold;
    }
</style>
@endsection

@section('section')
<section class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header text-center">
                <h4>Persentase Identifikasi Tindak Lanjut BPK</h4>
            </div>
            <div class="card-body">
                <canvas id="pie-chart" width="255" height="255"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="row">
            @foreach ([
                ['color' => 'green', 'icon' => 'bi bi-bookmark-check-fill', 'title' => 'Tindak Lanjut Sesuai/Selesai', 'count' => $data_sesuai->count()],
                ['color' => 'red', 'icon' => 'bi bi-stopwatch-fill', 'title' => 'Tindak Lanjut Belum Sesuai/Selesai', 'count' => $data_belum_sesuai->count()],
                ['color' => 'blue', 'icon' => 'bi bi-clipboard-data-fill', 'title' => 'Tindak Lanjut Belum Ditindaklanjuti', 'count' => $data_belum_ditindaklanjuti->count()],
                ['color' => 'black', 'icon' => 'bi bi-bookmark-x-fill', 'title' => 'Tindak Lanjut Tidak Ditindaklanjuti', 'count' => $data_tidak_dapat_ditindaklanjuti->count()]
            ] as $item)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-4 d-flex justify-content-start align-items-center">
                                    <div class="stats-icon {{ $item['color'] }} mb-2">
                                        <i class="{{ $item['icon'] }} mb-2"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="text-muted font-semibold">{{ $item['title'] }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $item['count'] }} dari {{ $data->count() }} total Identifikasi</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <caption class="float-left"><h6>Progres Penyelesaian Tindak Lanjut Menurut Unit Kerja</h6></caption>
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Unit Kerja</th>
                                <th>Jumlah Tindak Lanjut</th>
                                <th>Sesuai</th>
                                <th>Belum Sesuai</th>
                                <th>Belum Ditindaklanjuti</th>
                                <th>Tidak Ditindaklanjuti</th>
                                <th>Persentase Penyelesaian</th>
                                <th>Sudah Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1 @endphp
                            @php $totalTindakLanjut = 0 @endphp
                            @php $totalSesuai = 0 @endphp
                            @php $totalBelumSesuai = 0 @endphp
                            @php $totalBelumDitindaklanjuti = 0 @endphp
                            @php $totalTidakDitindaklanjuti = 0 @endphp
                            @php $totalPersentasePenyelesaian = 0 @endphp
                            @php $totalSudahUpload = 0 @endphp
                            @foreach($unitKerjaList as $unitKerja)
                            <tr>
                                <td class="text-center">{{ $no }}</td>
                                <td>{{ $unitKerja['unit_kerja'] }}</td>
                                <td class="text-center">{{ $unitKerja['jumlah_tindak_lanjut'] }}</td>
                                <td class="text-center">{{ $unitKerja['jumlah_sesuai'] }}</td>
                                <td class="text-center">{{ $unitKerja['jumlah_belum_sesuai'] }}</td>
                                <td class="text-center">{{ $unitKerja['jumlah_belum_ditindaklanjuti'] }}</td>
                                <td class="text-center">{{ $unitKerja['jumlah_tidak_ditindaklanjuti'] }}</td>
                                <td class="text-center">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $unitKerja['persentase_penyelesaian'] }}%;" aria-valuenow="{{ $unitKerja['persentase_penyelesaian'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    {{ number_format($unitKerja['persentase_penyelesaian'], 2) }}%
                                </td>
                                <td class="text-center">{{ $unitKerja['sudah_upload'] }}</td>
                            </tr>
                            @php
                                $totalTindakLanjut += $unitKerja['jumlah_tindak_lanjut'];
                                $totalSesuai += $unitKerja['jumlah_sesuai'];
                                $totalBelumSesuai += $unitKerja['jumlah_belum_sesuai'];
                                $totalBelumDitindaklanjuti += $unitKerja['jumlah_belum_ditindaklanjuti'];
                                $totalTidakDitindaklanjuti += $unitKerja['jumlah_tidak_ditindaklanjuti'];
                                $totalPersentasePenyelesaian += $unitKerja['persentase_penyelesaian'];
                                $totalSudahUpload += $unitKerja['sudah_upload'];
                                $no++
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="font-weight-bold text-center"><strong>Total</strong></td>
                                <td class="text-center"><strong>{{ $totalTindakLanjut }}</strong></td>
                                <td class="text-center"><strong>{{ $totalSesuai }}</strong></td>
                                <td class="text-center"><strong>{{ $totalBelumSesuai }}</strong></td>
                                <td class="text-center"><strong>{{ $totalBelumDitindaklanjuti }}</strong></td>
                                <td class="text-center"><strong>{{ $totalTidakDitindaklanjuti }}</strong></td>
                                <td class="text-center"><strong>{{ number_format($totalPersentasePenyelesaian / count($unitKerjaList), 2) }}%</strong></td>
                                <td class="text-center"><strong>{{ $totalSudahUpload }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="{{ asset('mazer/assets/extensions/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('mazer/assets/static/js/pages/ui-chartjs.js') }}"></script>

<!-- Pie Chart Rekomendasi -->
<script>
    var ctx = document.getElementById('pie-chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Sudah Didientifikasi',
                'Belum Diidentifikasi',
            ],
            datasets: [{
                data: [
                    {{  $data_sudah_diidentifikasi->count()}},
                    {{  $data_belum_diidentifikasi->count()}},
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true, // Membuat chart responsif
            maintainAspectRatio: false, // Membuat chart tidak mempertahankan aspek rasio
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'rgba(0, 0, 0, 0.8)', // Warna teks legenda
                        font: {
                            size: 12 // Ukuran font legenda
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            var value = context.formattedValue || '';
                            var dataset = context.dataset || {};
                            var total = dataset.data.reduce((previousValue, currentValue) => previousValue + currentValue, 0);
                            var percentage = Math.round((value / total) * 100);
                            value += ' (' + percentage + '%)';
                            return label + value;
                        }
                    }
                }
            },
            animation: {
                duration: 2000, // Durasi animasi
                easing: 'easeInOutQuart' // Jenis animasi
            }
        }
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

        // Bahasa Indonesia
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

        // Mengatur dom untuk memposisikan kotak pencarian di pojok kanan atas tabel
        dom: '<"d-flex justify-content-end mb-4"fB>rt<"d-flex justify-content-between mt-4"<"d-flex justify-content-start"li><"col-md-6"p>>',
    });
</script>
@endsection
