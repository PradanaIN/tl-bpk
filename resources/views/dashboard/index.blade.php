@extends('layouts.horizontal')

@section('style')
<style>
.stats-icon i {
    font-size: 16px; /* Ubah ukuran ikon sesuai kebutuhan */
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
                ['color' => 'green', 'icon' => 'bi bi-bookmark-check-fill', 'title' => 'Rekomendasi Sesuai/Selesai', 'count' => $rekomendasi_sesuai->count()],
                ['color' => 'red', 'icon' => 'bi bi-stopwatch-fill', 'title' => 'Rekomendasi Belum Sesuai/Selesai', 'count' => $rekomendasi_belum_sesuai->count()],
                ['color' => 'blue', 'icon' => 'bi bi-clipboard-data-fill', 'title' => 'Rekomendasi Belum Ditindaklanjuti', 'count' => $rekomendasi_belum_ditindaklanjuti->count()],
                ['color' => 'black', 'icon' => 'bi bi-bookmark-x-fill', 'title' => 'Rekomendasi Tidak Ditindaklanjuti', 'count' => $rekomendasi_tidak_dapat_ditindaklanjuti->count()]
            ] as $data)
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-4 col-xxl-3 d-flex justify-content-start align-items-center">
                                    <div class="stats-icon {{ $data['color'] }} mb-2">
                                        <i class="{{ $data['icon'] }} mb-2"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-8 col-xxl-9">
                                    <h6 class="text-muted font-semibold">{{ $data['title'] }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $data['count'] }} dari {{ $rekomendasi->count() }} total rekomendasi</h6>
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
                        <h4>Persentase Tindak Lanjut Rekomendasi BPK</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-chart" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6"> <!-- Kolom untuk line chart -->
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Jumlah Rekomendasi Pertahun</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="line-chart" width="300" height="300"></canvas>
                    </div>
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
                'Sesuai',
                'Belum Sesuai',
                'Belum Ditindaklanjuti',
                'Tidak Ditindaklanjuti'
            ],
            datasets: [{
                data: [
                    {{ $rekomendasi_sesuai->count() }},
                    {{ $rekomendasi_belum_sesuai->count() }},
                    {{ $rekomendasi_belum_ditindaklanjuti->count() }},
                    {{ $rekomendasi_tidak_dapat_ditindaklanjuti->count() }}
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
            labels: {!! $jumlah_rekomendasi_per_tahun->pluck('tahun') !!},
            datasets: [{
                label: 'Jumlah Rekomendasi per Tahun',
                data: {!! $jumlah_rekomendasi_per_tahun->pluck('jumlah_rekomendasi') !!},
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

@endsection
