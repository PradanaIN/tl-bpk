@extends('layouts.horizontal')

@section('style')
<style>
.stats-icon i {
    font-size: 16px; /* Ubah ukuran ikon sesuai kebutuhan */
}
</style>
@endsection

@section('filter')
<div class="row">
    <div class="col-auto">
        <label for="filterSelect" class="form-label"><i class="bi bi-filter"></i>&nbsp;Filter&nbsp;:&nbsp;</label>
        <select id="filterSelect" class="mr-2">
            <option value="umum">Umum</option>
            <option value="bps_daerah">BPS Daerah</option>
        </select>

    </div>
</div>
@endsection

@section('section')
<section class="row">
    <div id="dataContainer" class="row">
        <!-- Data Card Filter -->
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PIC</th>
                                <th>Jumlah Rekomendasi</th>
                                <th>Selesai</th>
                                <th>Belum Selesai</th>
                                <th>Belum Ditindaklanjuti</th>
                                <th>Tidak Dapat Ditindaklanjuti</th>
                                <th>Persentase</th>
                                <th>Sudah Upload</th>
                            </tr>
                        </thead>
                        <tbody id="tablebody">
                            <!-- Data Table Filter-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

    {{-- <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Jumlah Rekomendasi Belum Selesai</h4>
                </div>
                <div class="card-body">
                    <canvas id="bar"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Jumlah Rekomendasi Belum Selesai Pertahun Pemeriksaan</h4>
                </div>
                <div class="card-body">
                    <canvas id="line"></canvas>
                </div>
            </div>
        </div>
    </div> --}}

@section('script')

<!-- javascript for table filter -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mendapatkan elemen filter
        var filterSelect = document.getElementById('filterSelect');

        // Mendapatkan container untuk menampilkan data
        var dataContainer = document.getElementById('dataContainer');
        var tableBody = document.getElementById('tablebody'); // Perhatikan ID yang sesuai

        // Mendefinisikan data berdasarkan filter card
        var dataCard = {
            umum: {
                rekomendasi_selesai: '100',
                rekomendasi_belum_selesai: '200',
                belum_ditindaklanjuti: '300',
                tidak_dapat_ditindaklanjuti: '100'
            },
            bps_daerah: {
                rekomendasi_selesai: '50',
                rekomendasi_belum_selesai: '100',
                belum_ditindaklanjuti: '150',
                tidak_dapat_ditindaklanjuti: '50'
            }
        };

        // Objek untuk teks rekomendasi
        var jenisRekomendasiText = {
            rekomendasi_selesai: 'Rekomendasi Selesai',
            rekomendasi_belum_selesai: 'Rekomendasi Belum Selesai',
            belum_ditindaklanjuti: 'Rekomendasi Belum Ditindaklanjuti',
            tidak_dapat_ditindaklanjuti: 'Rekomendasi Tidak Dapat Ditindaklanjuti'
        };

        // Objek untuk ikon rekomendasi
        var jenisRekomendasiIcon = {
            rekomendasi_selesai: '<div class="stats-icon green"><i class="bi bi-bookmark-check-fill mb-2"></i></div>',
            rekomendasi_belum_selesai: '<div class="stats-icon blue"><i class="bi bi-stopwatch-fill mb-2"></i></div>',
            belum_ditindaklanjuti: '<div class="stats-icon red"><i class="bi bi-clipboard-data-fill mb-2"></i></div>',
            tidak_dapat_ditindaklanjuti: '<div class="stats-icon black"><i class="bi bi-bookmark-x-fill mb-2"></i></div>'
        };

        // Mendefinisikan data berdasarkan filter table
        var dataTable = {
            umum: [
                { no: 1, pic: 'PIC 1', jumlah: 250, selesai: 100, belum_selesai: 50, belum_ditindaklanjuti: 75, tidak_dapat_ditindaklanjuti: 25, persentase: '40%', upload: 'Ya' },
                { no: 2, pic: 'PIC 2', jumlah: 300, selesai: 150, belum_selesai: 50, belum_ditindaklanjuti: 50, tidak_dapat_ditindaklanjuti: 50, persentase: '60%', upload: 'Tidak' },
                { no: 3, pic: 'PIC 3', jumlah: 200, selesai: 75, belum_selesai: 25, belum_ditindaklanjuti: 50, tidak_dapat_ditindaklanjuti: 50, persentase: '30%', upload: 'Ya' }
            ],
            bps_daerah: [
                { no: 1, pic: 'PIC 1', jumlah: 150, selesai: 70, belum_selesai: 30, belum_ditindaklanjuti: 40, tidak_dapat_ditindaklanjuti: 10, persentase: '50%', upload: 'Ya' },
                { no: 2, pic: 'PIC 2', jumlah: 200, selesai: 90, belum_selesai: 40, belum_ditindaklanjuti: 40, tidak_dapat_ditindaklanjuti: 30, persentase: '45%', upload: 'Tidak' },
                { no: 3, pic: 'PIC 3', jumlah: 180, selesai: 80, belum_selesai: 30, belum_ditindaklanjuti: 40, tidak_dapat_ditindaklanjuti: 30, persentase: '55%', upload: 'Ya' }
            ]
        };

        // Fungsi untuk menampilkan data berdasarkan filter yang dipilih
        function updateData(filter) {
            // Bersihkan container data sebelum menambahkan data baru
            dataContainer.innerHTML = '';
            tableBody.innerHTML = '';

            // Membuat array dari setiap jenis rekomendasi
            var jenisRekomendasi = ['rekomendasi_selesai', 'rekomendasi_belum_selesai', 'belum_ditindaklanjuti', 'tidak_dapat_ditindaklanjuti'];

            // Membuat card untuk setiap jenis rekomendasi
            jenisRekomendasi.forEach(function (jenis) {
                var card = document.createElement('div');
                card.className = 'col-6 col-lg-3 col-md-6';
                card.innerHTML = `
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-xl-3 col-xxl-3 d-flex justify-content-start align-content-center">
                                    ${jenisRekomendasiIcon[jenis]}
                                </div>
                                <div class="col-md-8 col-lg-8 col-xl-9 col-xxl-9">
                                    <h6 class="text-muted font-semibold">${jenisRekomendasiText[jenis]}</h6>
                                    <h6 class="font-extrabold mb-0">${dataCard[filter][jenis]}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                // Tambahkan card ke container data
                dataContainer.appendChild(card);
            });

            // Membuat array dari setiap jenis rekomendasi
            var dataToShow = dataTable[filter];

            // Memasukkan data ke dalam tabel
            dataToShow.forEach(function (item) {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.no}</td>
                    <td>${item.pic}</td>
                    <td>${item.jumlah}</td>
                    <td>${item.selesai}</td>
                    <td>${item.belum_selesai}</td>
                    <td>${item.belum_ditindaklanjuti}</td>
                    <td>${item.tidak_dapat_ditindaklanjuti}</td>
                    <td>${item.persentase}</td>
                    <td>${item.upload}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Panggil fungsi pertama kali untuk menampilkan data default
        updateData(filterSelect.value);

        // Tambahkan event listener untuk mendeteksi perubahan filter
        filterSelect.addEventListener('change', function () {
            updateData(this.value); // Perbarui data berdasarkan filter yang dipilih
        });
    });
</script>

<!-- javascript for datatable -->
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('line').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
            datasets: [{
                label: 'Jumlah Rekomendasi Belum Selesai Pertahun Pemeriksaan',
                data: {!! json_encode($rekomendasi_belum_selesai_pertahun_pemeriksaan) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'x',
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }

    });

</script>
@endsection
