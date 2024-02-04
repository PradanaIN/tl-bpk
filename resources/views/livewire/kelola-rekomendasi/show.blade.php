@extends('layouts.vertical')


@section('section')

<section class="row">
    <div class="row d-flex justify-content-end mb-3">
        <div class="col-2">
            <form action="/kelola-rekomendasi/{{ $rekomendasi->id }}" method="post" class="d-inline">
                @method('delete')
                @csrf
                <button class="btn btn-danger" onclick="return confirm('Hapus Data?')">Hapus</button>
            </form>
        </div>
        <div class="col-2">
            <a href="/kelola-rekomendasi/{{ $rekomendasi->id }}/edit" class="btn btn-primary">Ubah Rekomendasi</a>
        </div>
    </div>
    <div class="card">
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
            <p><b>Tindak Lanjut : </b>{{ $rekomendasi->tindak_lanjut }}</p>
            <p><b>Unit Kerja : </b>{{ $rekomendasi->unit_kerja }}</p>
            <p><b>Tim Pemantauan : </b>{{ $rekomendasi->tim_pemantauan }}</p>
            <p><b>Tenggat Waktu : </b>{{ $rekomendasi->tenggat_waktu }}</p>
        </div>
    </div>
</section>

@endsection
