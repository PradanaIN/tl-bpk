@extends('layouts.horizontal')

@section('style')
<style>
.stats-icon i {
    font-size: 16px; /* Ubah ukuran ikon sesuai kebutuhan */
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
@endsection
