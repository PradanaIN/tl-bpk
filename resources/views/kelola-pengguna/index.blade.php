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
                                <th>Email</th>
                                <th>Unit Kerja</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->unit_kerja }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <div class="d-flex justify-content-around align-items-center">
                                            <a href="/kelola-pengguna/{{ $user->id }}/edit" class="btn btn-light"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Pengguna">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/kelola-pengguna/{{ $user->id }}" method="post" class="d-inline"
                                                id="deleteFormPengguna{{ $user->id }}">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-danger deleteButtonPengguna" type="button" data-form-id="deleteFormPengguna{{ $user->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Pengguna">
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
            text: '<i class="bi bi-plus"></i><span class="d-none d-md-inline"> Tambah Pengguna</span>',
            className: 'btn btn-primary',
            action: function(e, dt, node, config) {
                window.location.href = '/kelola-pengguna/create';
            }
        }]
    });
</script>

<script>
    $(document).ready(function () {
        $(document).on('click', '.deleteButtonPengguna', function () {
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
