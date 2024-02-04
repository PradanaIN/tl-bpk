@extends('layouts.vertical')


@section('section')

<section class="row">
    <div class="row d-flex justify-content-end mb-3">
        <div class="col-2">
            <a href="/kelola-kamus/create" class="btn btn-primary">Tambah Kamus</a>
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
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Ditambahkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kamus as $kamus)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kamus->nama }}</td>
                            <td>{{ $kamus->jenis }}</td>
                            <td>{{ $kamus->created_at }}</td>
                            <td>
                                <a href="/kelola-kamus/{{ $kamus->id }}/edit" class="btn btn-warning">Edit</a>
                                <form action="/kelola-kamus/{{ $kamus->id }}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-danger" onclick="return confirm('Hapus Data?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection
