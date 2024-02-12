@extends('layouts.vertical')

@section('style')
<style>
    /* CSS untuk menyejajarkan titik dua */
    .label {
        display: inline-block;
        width: 150px; /* Atur lebar label sesuai kebutuhan */
        font-weight: bold;
    }

    /* CSS untuk menampilkan baris teks */
    .text {
        display: inline-block;
        width: calc(100% - 150px); /* Atur lebar teks */
    }
</style>
@endsection

@section('section')

<section class="row">
    <div class="row mb-3 flex-wrap">
        <div class="col-auto d-flex me-auto">
            <a href="/kelola-rekomendasi" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
            <div class="col-auto">
                <a href="/kelola-rekomendasi/{{ $rekomendasi->id }}/edit" class="btn btn-light">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="/kelola-rekomendasi/{{ $rekomendasi->id }}" method="post" class="d-inline" id="deleteForm">
                    @method('delete')
                    @csrf
                    <button class="btn btn-danger" type="button" id="deleteButton">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><b>A. Detail Pemeriksaan</b></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Pemeriksaan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->pemeriksaan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Tahun</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->tahun_pemeriksaan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Jenis Pemeriksaan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->jenis_pemeriksaan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Hasil Pemeriksaan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->hasil_pemeriksaan }}</p>
                </div>
            </div>
        </div>
        <div class="card-header">
            <h4 class="card-title"><b>B. Detail Rekomendasi</b></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Jenis Temuan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->jenis_temuan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Uraian Temuan</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->uraian_temuan }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Rekomendasi</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->rekomendasi }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p class="fw-bold">Catatan Rekomendasi</p>
                </div>
                <div class="col-auto">:</div>
                <div class="col">
                    <p>{{ $rekomendasi->catatan_rekomendasi }}</p>
                </div>
            </div>
        </div>
        <div class="card-header">
            <h4 class="card-title"><b>C. Tindak Lanjut</b></h4>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="border: 1px solid black; padding: 10px;">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tindak Lanjut</th>
                            <th>Unit Kerja</th>
                            <th>Tim Pemantauan</th>
                            <th>Tenggat Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekomendasi->tindakLanjut as $index => $tindakLanjut)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tindakLanjut->tindak_lanjut }}</td>
                                <td>{{ $tindakLanjut->unit_kerja }}</td>
                                <td>{{ $tindakLanjut->tim_pemantauan }}</td>
                                <td>{{ $tindakLanjut->tenggat_waktu }}</td>
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

@endsection
