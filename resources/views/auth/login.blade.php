<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" type="image/x-icon"
        sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" type="image/png"
        sizes="32x32">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <title>TL BPK - Login</title>

    <link rel="stylesheet" href="{{ asset('mazer/assets/auth/css/style.css') }}">

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="img"
                            style="background-image: url({{ asset('mazer/assets/auth/images/bg-login.jpg') }});"></div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100 me-auto d-flex justify-content-center align-items-center">
                                    <img class="mb-3"
                                        src="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" alt="Logo"
                                        style="width: 50px; height: auto;">
                                    <h3 class="mb-3 ml-3"><b>TL BPK</b></h3>
                                </div>
                            </div>
                            <form action="/auth/login" method="post" class="signin-form">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        value="@error('email'){{ old('email') }}@else{{ old('email') }}@enderror"
                                        name="email" placeholder="Email" required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Password"
                                        required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">
                                        <h5 class="text-white">Login</h5>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('mazer/assets/auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/auth/js/popper.js') }}"></script>
    <script src="{{ asset('mazer/assets/auth/js/bootstrap.min.js') }}"></script>


    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('error') }}',
            })
        </script>
    @elseif (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                showConfirmButton: false,
                timer: 1500,
                text: '{{ session('success') }}',
            })
        </script>
    @endif

</body>

</html>
