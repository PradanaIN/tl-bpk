@extends('layouts.horizontal')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection


@section('section')

<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="row">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-vertical" action="/master-pengguna/{{ $user->id }}" method="post">
                            @method('put')
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="nama">Nama</label>
                                                <input type="text" id="nama" class="form-control"
                                                    name="nama" placeholder="Nama" value="{{ old('nama', $user->nama) }}" required>
                                                @error('nama')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" class="form-control"
                                                    name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="unit_kerja">Unit Kerja</label>
                                                <select class="form-select"  id="unit_kerja_id" name="unit_kerja_id" required>
                                                    <option value="{{ old('unit_kerja_id', $user->unit_kerja_id) }}">{{ $user->unit_kerja }}</option>
                                                    @foreach($unit_kerja as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" id="unit_kerja" name="unit_kerja" value="{{ $user->unit_kerja }}">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="role">Role</label>
                                                <select class="form-select" id="role" name="role" required>
                                                    <option value="{{ old('role', $user->role) }}">{{ $user->role }}</option>
                                                    @foreach ($role as $role)
                                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" id="password" class="form-control" name="password" placeholder="Password" value="{{ old('password', $user->password) }}" required>
                                                        <button class="btn btn-secondary" type="button" id="togglePassword">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                    <div class="col-12 d-flex justify-between justify-content-end mt-5">
                                        <button type="reset" class="btn btn-light-secondary me-3 mb-1">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


@section('script')

<script>
    $(document).ready(function() {
        $('#unit_kerja_id').change(function() {
            var selectedUnit = $(this).children("option:selected").text();
            $('#unit_kerja').val(selectedUnit);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('active');
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// select2
$(document).ready(function() {
    $('.form-select').select2({
        theme: 'bootstrap-5',
    });
});
</script>


<script>
    // warning batal button
    document.querySelector('.btn-light-secondary').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data yang sudah diinputkan tidak akan tersimpan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/master-pengguna";
            }
        })
    });
</script>

@endsection
