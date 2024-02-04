@extends('layouts.vertical')


@section('section')


<section class="row">
    <div class="row d-flex justify-content-end mb-3">
        <div class="col-2">
            <a href="/kelola-rekomendasi/create" class="btn btn-primary">Tambah Rekomendasi</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pemeriksaan</th>
                            <th>Tahun</th>
                            <th>Temuan</th>
                            <th>Uraian</th>
                            <th>Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekomendasi as $rekomendasi)
                        <tr class='clickable-row' data-href="/kelola-rekomendasi/{{ $rekomendasi->id }}}}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rekomendasi->pemeriksaan }}</td>
                            <td>{{ $rekomendasi->tahun_pemeriksaan }}</td>
                            <td>{{ $rekomendasi->jenis_temuan }}</td>
                            <td>{{ $rekomendasi->uraian_temuan }}</td>
                            <td>{{ $rekomendasi->rekomendasi }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection

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
