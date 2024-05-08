<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - TL BPK</title>

    <link rel="icon" href="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/error.css') }}">
</head>

<body>
    <script src="{{ asset('mazer/assets/static/js/initTheme.js') }}"></script>
    <div id="error">

<div class="error-page container">
    <div class="col-md-8 col-12 offset-md-2">
        <div class="text-center">
            <img class="img-error" src="{{ asset('mazer/assets/compiled/svg/error-403.svg') }}" alt="Forbidden">
            <h1 class="error-title">Forbidden</h1>
            <p class="fs-5 text-gray-600">You are unauthorized to see this page.</p>
            <a href="/dashboard" class="btn btn-lg btn-outline-primary mt-3">Go Home</a>
        </div>
    </div>
</div>


    </div>
</body>

</html>
