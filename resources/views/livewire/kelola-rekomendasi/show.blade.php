@extends('layouts.vertical')


@section('section')

<section class="row">
    <div class="card">
        <div class="row d-flex justify-content-end mb-3">
            <div class="col-auto">
                <a href="/kelola-rekomendasi/{{ $rekomendasi->id }}/edit" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i>
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
        <div class="card-header">
            <h4 class="card-title"><b>A. Detail Pemeriksaan</b></h4>
        </div>
        <div class="card-body">
            <p><b>Pemeriksaan : </b>{{ $rekomendasi->pemeriksaan }}</p>
            <p><b>Tahun : </b>{{ $rekomendasi->tahun_pemeriksaan }}</p>
            <p><b>Jenis Pemeriksaan : </b>{{ $rekomendasi->jenis_pemeriksaan }}</p>
            <p><b>Hasil Pemeriksaan : </b>{{ $rekomendasi->hasil_pemeriksaan }}</p>
        </div>
        <div class="card-header">
            <h4 class="card-title"><b>B. Detail Rekomendasi</b></h4>
        </div>
        <div class="card-body">
            <p><b>Jenis Temuan : </b>{{ $rekomendasi->jenis_temuan }}</p>
            <p><b>Uraian Temuan : </b>{{ $rekomendasi->uraian_temuan }}</p>
            <p><b>Rekomendasi : </b>{{ $rekomendasi->rekomendasi }}</p>
            <p><b>Catatan Rekomendasi : </b>{{ $rekomendasi->catatan_rekomendasi }}</p>
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
