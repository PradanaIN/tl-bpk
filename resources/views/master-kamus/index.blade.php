@extends('layouts.horizontal')


@section('section')
    <section class="row">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <thead class="thead-light">
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
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $kamus->nama }}</td>
                                    <td>{{ $kamus->jenis }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($kamus->created_at)->translatedFormat(' d M Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-around align-items-center">
                                            <a href="/master-kamus/{{ $kamus->id }}/edit" class="btn btn-light"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Kamus">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/master-kamus/{{ $kamus->id }}" method="post" class="d-inline"
                                                id="deleteFormKamus{{ $kamus->id }}">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-danger" type="button" id="deleteButtonKamus" data-form-id="deleteFormKamus{{ $kamus->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Kamus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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


@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);
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
            buttons: [{
                text: '<i class="bi bi-plus"></i><span class="d-none d-md-inline"> Tambah Kamus</span>',
                className: 'btn btn-primary',
                action: function(e, dt, node, config) {
                    window.location.href = '/master-kamus/create';
                }
            }]
        });
    </script>

<script>
    $(document).ready(function () {
        $(document).on('click', '.deleteButtonKamus', function () {
            let formId = $(this).attr('data-form-id');
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
                    $('#' + formId).submit();
                }
            })
        });
    });

        @if (session()->has('create'))
            Swal.fire({
                title: 'Success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('create') }}'
            });
        @elseif (session()->has('update'))
            Swal.fire({
                title: 'Success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('update') }}'
            });
        @elseif (session()->has('delete'))
            Swal.fire({
                title: 'Success',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('delete') }}'
            });
        @elseif (session()->has('error'))
            Swal.fire({
                title: 'Error',
                icon: 'error',
                text: '{{ session('error') }}'
            });
        @endif
</script>
@endsection
