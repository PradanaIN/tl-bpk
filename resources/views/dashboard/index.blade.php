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

@section('filter')
{{-- <div class="row">
    <div class="col-auto">
        <label for="filterSelect" class="form-label"><i class="bi bi-filter"></i>&nbsp;Filter&nbsp;:&nbsp;</label>
        <select id="filterSelect" class="mr-2">
            <option value="umum">Umum</option>
            <option value="bps_daerah">BPS Daerah</option>
        </select>

    </div>
</div> --}}
@endsection

@section('section')
<section class="row">
    <div class="col-12">
        <div class="row">
            @foreach ([
                ['color' => 'green', 'icon' => 'bi bi-bookmark-check-fill', 'title' => ($role === 'Operator Unit Kerja') ? 'Tindak Lanjut Sesuai/Selesai' : 'Rekomendasi Sesuai/Selesai', 'count' => $data_sesuai->count()],
                ['color' => 'red', 'icon' => 'bi bi-stopwatch-fill', 'title' => ($role === 'Operator Unit Kerja') ? 'Tindak Lanjut Belum Sesuai/Selesai' : 'Rekomendasi Belum Sesuai/Selesai', 'count' => $data_belum_sesuai->count()],
                ['color' => 'blue', 'icon' => 'bi bi-clipboard-data-fill', 'title' => ($role === 'Operator Unit Kerja') ? 'Tindak Lanjut Belum Ditindaklanjuti' : 'Rekomendasi Belum Ditindaklanjuti', 'count' => $data_belum_ditindaklanjuti->count()],
                ['color' => 'black', 'icon' => 'bi bi-bookmark-x-fill', 'title' => ($role === 'Operator Unit Kerja') ? 'Tindak Lanjut Tidak Ditindaklanjuti' : 'Rekomendasi Tidak Ditindaklanjuti', 'count' => $data_tidak_dapat_ditindaklanjuti->count()]
            ] as $item)
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-4 col-xxl-3 d-flex justify-content-start align-items-center">
                                    <div class="stats-icon {{ $item['color'] }} mb-2">
                                        <i class="{{ $item['icon'] }} mb-2"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-8 col-xxl-9">
                                    <h6 class="text-muted font-semibold">{{ $item['title'] }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $item['count'] }} dari {{ $data->count() }} total {{ ($role === 'Operator Unit Kerja') ? 'tindak lanjut' : 'rekomendasi' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <div class="row"> <!-- Baris untuk kedua grafik -->
            <div class="col-12 col-lg-6"> <!-- Kolom untuk pie chart -->
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Persentase Status {{ ($role === 'Operator Unit Kerja') ? 'Tindak Lanjut' : 'Rekomendasi' }} BPK</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-chart" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6"> <!-- Kolom untuk line chart -->
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Jumlah {{ ($role === 'Operator Unit Kerja') ? 'Tindak Lanjut' : 'Rekomendasi' }} Pertahun</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="line-chart" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($role === 'Super Admin' || $role === 'Tim Koordinator')
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
                            @php $totalRekomendasi = 0 @endphp
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
                                <td class="text-center">{{ $unitKerja['jumlah_rekomendasi'] }}</td>
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
                                $totalRekomendasi += $unitKerja['jumlah_rekomendasi'];
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
                                <td class="text-center"><strong>{{ $totalRekomendasi }}</strong></td>
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
    @endif
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
                'Sesuai',
                'Belum Sesuai',
                'Belum Ditindaklanjuti',
                'Tidak Ditindaklanjuti'
            ],
            datasets: [{
                data: [
                    {{ $data_sesuai->count() }},
                    {{ $data_belum_sesuai->count() }},
                    {{ $data_belum_ditindaklanjuti->count() }},
                    {{ $data_tidak_dapat_ditindaklanjuti->count() }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
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

<!-- Line Chart -->
<script>
    var ctx = document.getElementById('line-chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! collect($jumlah_data_per_tahun)->pluck('tahun') !!},
            datasets: [{
                label: 'Jumlah Rekomendasi per Tahun',
                data: {!! collect($jumlah_data_per_tahun)->pluck('jumlah') !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Warna area di bawah garis
                borderColor: 'rgba(54, 162, 235, 1)', // Warna garis
                borderWidth: 2, // Ketebalan garis
                pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Warna titik
                pointRadius: 5, // Ukuran titik
                pointHoverRadius: 7, // Ukuran titik saat dihover
                tension: 0.4 // Mengatur ketegangan kurva
            }]
        },
        options: {
            responsive: true, // Membuat chart responsif
            maintainAspectRatio: false, // Membuat chart tidak mempertahankan aspek rasio
            scales: {
                y: {
                    suggestedMin: 0,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)' // Warna gridlines
                    },
                    ticks: {
                        color: 'rgba(0, 0, 0, 0.7)', // Warna label
                        font: {
                            size: 12, // Ukuran font
                            weight: 'bold' // Ketebalan font
                        }
                    }
                },
                x: {
                    grid: {
                        display: false // Sembunyikan gridlines untuk sumbu X
                    },
                    ticks: {
                        color: 'rgba(0, 0, 0, 0.7)', // Warna label
                        font: {
                            size: 12, // Ukuran font
                            weight: 'bold' // Ketebalan font
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true, // Tampilkan legenda
                    labels: {
                        color: 'rgba(0, 0, 0, 0.7)', // Warna teks legenda
                        font: {
                            size: 12, // Ukuran font legenda
                            weight: 'bold' // Ketebalan font legenda
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
