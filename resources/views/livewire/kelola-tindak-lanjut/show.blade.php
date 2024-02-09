@extends('layouts.vertical')

@section('style')
<style>

/* CSS untuk konten modal */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* Atur margin atas dan bawah dan kiri-kanan secara otomatis */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Sesuaikan lebar modal sesuai kebutuhan */
    border-radius: 10px; /* Tambahkan sudut bulat pada modal */
}

</style>
@endsection

@section('section')

<section class="row">
    <div class="row mb-3 flex-wrap">
        <div class="col-auto d-flex me-auto">
            <a href="/kelola-tindak-lanjut" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
        <div class="col-auto d-flex ms-auto">
            <button class="btn btn-primary" id="uploadBtn">
                <i class="bi bi-plus"></i>
                Upload Dokumen TL
            </button>
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
            <p><b>Tindak Lanjut : </b>{{ $tindak_lanjut->tindak_lanjut }}</p>
            <p><b>Unit Kerja : </b>{{ $tindak_lanjut->unit_kerja }}</p>
            <p><b>Tim Pemantauan : </b>{{ $tindak_lanjut->tim_pemantauan }}</p>
            <p><b>Tenggat Waktu : </b>{{ $tindak_lanjut->tenggat_waktu }}</p>
            <p><b>Dokumen TL : </b>{{ $tindak_lanjut->dokumen_tindak_lanjut }}</p>
            <p><b>Detail Dokumen TL : </b>{{ $tindak_lanjut->detail_dokumen_tindak_lanjut }}</p>
        </div>
    </div>
</section>

@endsection


<!-- modal upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Dokumen Tindak Lanjut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/kelola-tindak-lanjut/{{ $tindak_lanjut->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="modal-body
                ">
                <!-- input dengan tipe hidden untuk data yang sbeelumnya -->
                <input type="hidden" name="tindak_lanjut" value="{{ $tindak_lanjut->tindak_lanjut }}">
                <input type="hidden" name="unit_kerja" value="{{ $tindak_lanjut->unit_kerja }}">
                <input type="hidden" name="tim_pemantauan" value="{{ $tindak_lanjut->tim_pemantauan }}">
                <input type="hidden" name="tenggat_waktu" value="{{ $tindak_lanjut->tenggat_waktu }}">
                <input type="hidden" name="rekomendasi_id" value="{{ $tindak_lanjut->rekomendasi_id }}">

                <div class="mb-3">
                    <label for="dokumen_tindak_lanjut" class="form-label">Dokumen Tindak Lanjut</label>
                    <input type="text" class="form-control" id="dokumen_tindak_lanjut" name="dokumen_tindak_lanjut">
                </div>
                <div class="mb-3">
                    <label for="dokumen_tindak_lanjut" class="form-label">Detail Dokumen Tindak Lanjut</label>
                    <textarea class="form-control" id="detail_dokumen_tindak_lanjut" name="detail_dokumen_tindak_lanjut" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
            </form>
        </div>
    </div>
</div>


@section('script')

<script>
    // Ambil tombol "Upload Dokumen TL"
    var uploadBtn = document.getElementById('uploadBtn');

    // Tambahkan event listener untuk menampilkan modal saat tombol diklik
    uploadBtn.addEventListener('click', function() {
        var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        uploadModal.show();
    });

</script>

@endsection
