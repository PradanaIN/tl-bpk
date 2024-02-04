@
@extends('layouts.vertical')


@section('section')

<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="row">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-vertical" action="/kelola-pengguna" method="post">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Nama</label>
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                    name="nama" placeholder="Nama">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email-id-vertical">Email</label>
                                                <input type="email" id="email-id-vertical" class="form-control"
                                                    name="email" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="contact-info-vertical">Unit Kerja</label>
                                                <select class="form-select" id="basicSelect" name="unit_kerja">
                                                    <option value="Unit Kerja A">Unit Kerja A</option>
                                                    <option value="Unit Kerja B">Unit Kerja B</option>
                                                    <option value="Unit Kerja C">Unit Kerja C</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="password-vertical">Role</label>
                                                <select class="form-select" id="basicSelect" name="role">
                                                    <option value="Tim Koordinator">Tim Koordinator</option>
                                                    <option value="Unit Kerja">Unit Kerja</option>
                                                    <option value="Pimpinan">Pimpinan</option>
                                                </select>
                                            </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="password-vertical">Password</label>
                                                <input type="password" id="password-vertical" class="form-control"
                                                    name="password" placeholder="Password">
                                            </div>
                                        </div>
                                    <div class="col-12 d-flex justify-between justify-content-end mt-3">
                                        <button type="reset" class="btn btn-light-secondary me-3 mb-1">
                                            <a href="/kelola-pengguna" style="text-decoration: none; color: black;">Batal</a>
                                        </button>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Tambah</button>
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
