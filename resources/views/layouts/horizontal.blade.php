<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <link rel="icon" href="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" type="image/x-icon"
        sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" type="image/png"
        sizes="32x32">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet"
        href="{{ asset('mazer/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/filepond/filepond.css') }}" />
    <link rel="stylesheet" href="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.css') }}" />


    <style>
        .clickable-row:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        #table1 th {
            text-align: center;
        }

        /* Style untuk table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Style untuk header dan footer tabel */
        .table-header,
        .table-footer {
            font-weight: bold;
        }

        /* Style untuk baris ganjil */
        tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        /* Style untuk baris total */
        .table-footer td {
            background-color: #e9e9e9;
        }

        .menu a {
            text-decoration: none;
        }

        .navbar-toggler {
            border: none;
            font-size: 1.25rem;
        }

        .navbar-toggler:focus,
        .btn-close:focus {
            outline: none;
            box-shadow: none;
        }

        #navbar-link {
            color: #666777;
            font-weight: 500;
            position: relative;
        }

        #navbar-link:hover,
        #navbar-link.active {
            color: black;
        }

        @media (min-width: 991.98px) {
            #navbar-link::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                width: 0;
                height: 2px;
                background-color: black;
                transition: 0.3s;
                visibility: hidden;
                transform: translateX(-50%);
                transition: 0.3s ease-in-out;
            }

            #navbar-link:hover::before,
            #navbar-link.active::before {
                visibility: visible;
                width: 100%;
            }
        }

        .form-label {
            font-weight: bold;
        }

        .bold-blue-text {
            font-weight: bold;
            color: #25396f;
            /* Warna yang diinginkan */
        }

        .navbar-nav .nav-item {
            margin-right: 5px;
            /* Atur jarak antara setiap item menu */
        }

        .dropdown-header {
            border-bottom: 1px solid #e0e0e0;
        }


        /* Warna untuk notifikasi yang belum dibaca */
        .unread-notification {
            background-color: #f0f0f0;
            border-bottom: 1px solid #e0e0e0;
        }

        /* Ganti warna untuk notifikasi yang sudah dibaca */
        .read-notification {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }

        .dropdown-notification-scroll {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Media query untuk layar kecil */
        @media (max-width: 768px) {
            .dropdown-menu.notification-dropdown {
                max-width: 100vw;
                width: 100%;
                right: 0;
                left: 0;
            }

            .dropdown-item.notification-item .notification-text {
                white-space: normal;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .dropdown-notification-scroll {
                max-height: calc(100vh - 300px);
                overflow-y: auto;
            }
        }

        /* CUSTOM CSS FOR STATUS */
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            max-width: auto;
            /* Sesuaikan dengan lebar yang diinginkan */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .status-identifikasi {
            background-color: #FFD700;
            /* Kuning */
            color: #000000;
            /* Hitam */
        }

        .status-belum-sesuai {
            background-color: #FFD700;
            /* Kuning */
            color: #000000;
            /* Hitam */
        }

        .status-belum-ditindaklanjuti {
            background-color: #FF6347;
            /* Merah Terang */
            color: #FFFFFF;
            /* Putih */
        }

        .status-sesuai {
            background-color: #008000;
            /* Hijau */
            color: #FFFFFF;
            /* Putih */
        }

        .status-tidak-ditindaklanjuti {
            background-color: #808080;
            /* Abu-abu */
            color: #FFFFFF;
            /* Putih */
        }

        @media (max-width: 768px) {
            .custom-row {
                margin-bottom: 20px;
            }
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            /* Warna latar belakang semi-transparan */
            z-index: 9999;
            /* Pastikan spinner berada di atas konten lain */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner-grow {
            margin-right: 5px;
            margin-left: 5px;
        }
    </style>

    @yield('style')

</head>

<body>
    <div id="loadingOverlay" class="overlay">
        <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div id="app">
        <div id="main" class="layout-horizontal">
            <nav class="navbar navbar-expand-lg header-top fixed-top">
                <div class="container">
                    <a href="/dashboard" class="navbar-brand me-auto d-flex justify-content-start align-items-center">
                        <img src="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" alt="Logo"
                            style="width: 50px; height: auto;">
                        <h3>&ensp;TL BPK</h3>
                    </a>
                    <div class="d-flex justify-content-end">
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                            aria-labelledby="offcanvasNavbarLabel">
                            <div class="offcanvas-header">
                                <img src="{{ asset('mazer/assets/static/images/logo/logo-bps.png') }}" alt="Logo"
                                    style="width: 50px; height: auto;">
                                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">TL BPK</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                    <li class="nav-item">
                                        <a class="nav-link" id="navbar-link" href="/dashboard">
                                            <h6>Dashboard</h6>
                                        </a>
                                    </li>
                                    @canany(['Tim Koordinator', 'Super Admin', 'Pimpinan'])
                                        <li class="nav-item">
                                            <a class="nav-link" id="navbar-link" href="/rekomendasi">
                                                <h6>Rekomendasi</h6>
                                            </a>
                                        </li>
                                    @endcan
                                    @canany(['Super Admin', 'Tim Koordinator', 'Operator Unit Kerja', 'Pimpinan',
                                        'Pimpinan Unit Kerja'])
                                        <li class="nav-item">
                                            <a class="nav-link" id="navbar-link" href="/tindak-lanjut">
                                                <h6>Tindak Lanjut</h6>
                                            </a>
                                        </li>
                                    @endcan
                                    @canany([
                                        'Tim Pemantauan Wilayah I',
                                        'Tim Pemantauan Wilayah II',
                                        'Tim Pemantauan
                                        Wilayah III',
                                        'Super Admin',
                                        ])
                                        <li class="nav-item">
                                            <a class="nav-link" id="navbar-link" href="/identifikasi">
                                                <h6>Identifikasi</h6>
                                            </a>
                                        </li>
                                    @endcan
                                    @canany(['Super Admin', 'Tim Koordinator', 'Pimpinan'])
                                        <li class="nav-item">
                                            <a class="nav-link" id="navbar-link" href="/pemutakhiran-status">
                                                <h6>Pemutakhiran</h6>
                                            </a>
                                        </li>
                                    @endcan
                                    {{-- @canany(['Tim Koordinator', 'Pimpinan'])
                                        <li class="nav-item">
                                            <a class="nav-link" id="navbar-link" href="/old-rekomendasi">
                                                <h6>Rekomendasi Lama</h6>
                                            </a>
                                        </li>
                                    @endcan --}}
                                    @canany(['Admin'])
                                    <li class="nav-item">
                                        <a class="nav-link" id="navbar-link" href="/master-kamus">
                                            <h6>Master Kamus</h6>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="navbar-link" href="/kelola-pengguna">
                                            <h6>Kelola Pengguna</h6>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="navbar-link" href="/old-rekomendasi">
                                            <h6>Rekomendasi Lama</h6>
                                        </a>
                                    </li>
                                    @endcan
                                    @canany(['Super Admin'])
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle bold-blue-text" href="#"
                                                id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Admin
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropright"
                                                aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item text-center" href="/master-kamus">
                                                    <h6>Master Kamus</h6>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-center" href="/kelola-pengguna">
                                                    <h6>Kelola Pengguna</h6>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-center" href="/old-rekomendasi">
                                                    <h6>Rekomendasi Lama</h6>
                                                </a>
                                            </div>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                        <div class="header-top-right">
                            <ul class="nav justify-content-end">
                                <li class="nav-item dropdown">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                                        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class='bi bi-bell bi-sub fs-4'></i>
                                        @if (auth()->user()->unreadNotifications->count() > 0)
                                            <span
                                                class="badge badge-notification bg-danger me-2">{{ auth()->user()->unreadNotifications->count() }}</span>
                                        @endif
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                        aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header">
                                            <h6>Daftar Notifikasi</h6>
                                        </li>
                                        <div class="dropdown-notification-scroll">
                                            @if (auth()->user()->notifications->isEmpty())
                                                <li>
                                                    <p class="text-center py-2 mb-0 text-black-50">Tidak terdapat
                                                        notifikasi</p>
                                                </li>
                                            @else
                                                @foreach (auth()->user()->notifications as $notification)
                                                    <li
                                                        class="dropdown-item notification-item {{ $notification->read_at ? 'read-notification' : 'unread-notification' }}">
                                                        <a class="d-flex align-items-center"
                                                            href="{{ isset($notification->data['url']) ? $notification->data['url'] : '#' }}"
                                                            data-notification-id="{{ $notification->id }}">
                                                            <div class="notification-text ms-3">
                                                                @if (isset($notification->data['title']) && !empty($notification->data['title']))
                                                                    <p
                                                                        class="notification-title font-bold mb-1 text-sm text-black">
                                                                        {{ $notification->data['title'] }}</p>
                                                                @endif
                                                                @if (isset($notification->data['message']) && !empty($notification->data['message']))
                                                                    <p
                                                                        class="notification-subtitle font-thin text-sm mb-0 text-black-50">
                                                                        {{ $notification->data['message'] }}</p>
                                                                @endif
                                                                <div
                                                                    class="notification-meta d-flex justify-content-between">
                                                                    @if (isset($notification->created_at))
                                                                        <p class="small mb-0 text-black-50">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </div>
                                        <li>
                                            <p class="text-center py-2 mb-0">Notifikasi dengan highlight adalah yang
                                                belum terbaca.</p>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-menu d-flex">
                                            <div class="user-name text-end me-3 d-none d-md-inline">
                                                <h6 class="mb-0 text-gray-60">{{ Auth::user()->nama }}</h6>
                                                <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->role }}</p>
                                            </div>
                                            <div class="user-img d-flex align-items-center">
                                                <div class="avatar avatar-md">
                                                    <img
                                                        src="https://w7.pngwing.com/pngs/178/595/png-transparent-user-profile-computer-icons-login-user-avatars-thumbnail.png">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                                        style="min-width: 11rem;">
                                        <li class="d-flex justify-content-center align-items-center">
                                            <div class="theme-toggle d-flex gap-2  align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                                    role="img" class="iconify iconify--system-uicons"
                                                    width="20" height="20" preserveAspectRatio="xMidYMid meet"
                                                    viewBox="0 0 21 21">
                                                    <g fill="none" fill-rule="evenodd" stroke="currentColor"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                                            opacity=".3"></path>
                                                        <g transform="translate(-210 -1)">
                                                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                                            <circle cx="220.5" cy="11.5" r="4"></circle>
                                                            <path
                                                                d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <div class="form-check form-switch fs-6">
                                                    <input class="form-check-input  me-0" type="checkbox"
                                                        id="toggle-dark" style="cursor: pointer">
                                                    <label class="form-check-label"></label>
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                                    role="img" class="iconify iconify--mdi" width="20"
                                                    height="20" preserveAspectRatio="xMidYMid meet"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </li>
                                        <hr class="dropdown-divider mt-2">
                                        <li>
                                            <form action="/auth/logout" method="post">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                                    Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
            </nav>


            <div class="content-wrapper container" style="margin-top: 110px;">
                <div class="page-heading d-flex justify-content-between align-items-center" style="width: 97%">
                    <h3>{{ $title }}</h3>
                    @yield('filter')
                </div>
                <div class="page-content" style="margin-top: -20px;">
                    @yield('section')
                </div>
            </div>
        </div>
    </div>

    <!-- javascript -->
    <script src="{{ asset('mazer/assets/static/js/pages/horizontal-layout.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/compiled/js/app.js') }}"></script>

    <!-- filepond -->
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}">
    </script>
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}">
    </script>
    <script
        src="{{ asset('mazer/assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}">
    </script>
    <script src="{{ asset('mazer/assets/extensions/filepond/filepond.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/filepond.js') }}"></script>

    <!-- tinymce -->
    <script src="{{ asset('mazer/assets/extensions/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/tinymce.js') }}"></script>

    <!-- custom spinner for loading page -->
    <script>
        // Sembunyikan spinner saat halaman selesai dimuat
        window.addEventListener('load', function() {
            document.getElementById('loadingOverlay').style.display = 'none';
        });

        // Tampilkan spinner sebagai overlay saat dokumen dimuat dan saat mengirim permintaan HTTP
        document.addEventListener('click', function(event) {
            if (event.target.tagName === 'A' || event.target.tagName === 'BUTTON') {
                document.getElementById('loadingOverlay').classList.add('overlay');
            }
        });
    </script>

    <script>
        const form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah pengiriman formulir secara otomatis

            // Tampilkan spinner saat pengiriman data dimulai
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Kirim formulir secara otomatis
            form.submit();
        });

        // Sembunyikan spinner setelah pengiriman data selesai
        form.addEventListener('load', function() {
            document.getElementById('loadingOverlay').style.display = 'none';
        });
    </script>

    <!-- custom tinymce -->
    <script>
        tinymce.init({
            forced_root_block: false,
            selector: "textarea",
            promotion: false,
            height: 185,
            plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            menubar: "table tools",
        });
    </script>

    <!-- flatpickr -->
    <script src="{{ asset('mazer/assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/date-picker.js') }}"></script>

    <!-- custom flatpickr -->
    <script>
        flatpickr('.flatpickr-no-config', {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            // minDate: "today",
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                },
                months: {
                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September',
                        'Oktober', 'November', 'Desember'
                    ],
                },
            },
        });
    </script>

    <!-- parsley -->
    <script src="{{ asset('mazer/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/parsley.js') }}"></script>

    <!-- data tables -->
    <script src="{{ asset('mazer/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('mazer/assets/static/js/pages/datatables.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

    <!-- sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            })
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}',
            })
        </script>
    @endif
    @if (session('success'))
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

    <script>
        // warning delete
        document.getElementById('deleteButton').addEventListener('click', function() {
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
                    document.getElementById('deleteForm').submit();
                }
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
        @elseif (session()->has('info'))
            Swal.fire({
                title: 'Info',
                icon: 'info',
                text: '{{ session('info') }}'
            });
        @endif
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const navLinks = document.querySelectorAll('#navbar-link');

            // Fungsi untuk menambahkan kelas 'active' ke tautan yang diklik
            function setActiveLink(link) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                });
                link.classList.add('active');
            }

            // Tambahkan event listener untuk setiap tautan
            navLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    setActiveLink(this);
                    // Simpan status menu yang aktif di localStorage
                    localStorage.setItem('activeMenu', this.getAttribute('href'));
                });

                // Periksa apakah ada menu yang aktif disimpan di localStorage
                const activeMenu = localStorage.getItem('activeMenu');
                if (activeMenu && link.getAttribute('href') === activeMenu) {
                    setActiveLink(link);
                }
            });
        });
    </script>

    @yield('script')


</body>

</html>
