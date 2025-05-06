<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>SiAR</title>
    <link rel="icon" href="{{ asset('assets/img/logo_title.png') }}" type="image/x-icon">

    <!-- KALAU MAU PAKAI TAILWIND -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />



    {{-- <!-- Aos scroll animate--> --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Page CSS -->
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        p {
            margin: 0;
        }

        .select2-container .select2-selection--single {
            padding: 10px !important;  
            height: auto !important; /* Opsional, agar tinggi menyesuaikan padding */
            }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important; /* Biar isi teks tidak terpotong */
            padding-left: 0 !important; /* Jika ingin mengatur ulang padding dalam teks */
        }

        /* Pastikan tinggi konsisten */
        .select2-container--default .select2-selection--single {
            height: 38px; /* atau sesuaikan dengan kebutuhan */
            padding: 0 12px;
            display: flex;
            align-items: center; /* ini menyelaraskan vertical */
        }

        /* Rendered value agar teks sejajar vertikal */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            line-height: normal;
            flex-grow: 1; /* biar isi teks dan panah sejajar */
            display: flex;
            align-items: center;
        }

        /* Arrow juga sejajar */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .box-dashboard {
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .box-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .box-dashboard .card {
            transition: all 0.3s ease;
        }

        .box-dashboard:hover .card {
            border-left-width: 4px !important;
        }

        .box-dashboard:hover .card-body {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .box-dashboard:hover .text-gray-300 {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        .box-dashboard:hover .text-primary,
        .box-dashboard:hover .text-success {
            font-weight: 700;
        }

    </style>
</head>

<body id="page-top">

    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            @if(isset($isAuth) && $isAuth === true)
                @yield('content_auth')
            @else
                @include('components.sidebar')
                <div class="layout-page">
                    @include('components.topbar')


                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="container-fluid" data-aos="fade-right">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @elseif (session('error'))
                                    @foreach (session('error') as $error)
                                        <div class="alert alert-danger">{{ $error }}</div>
                                    @endforeach
                                    {{-- <div class="alert alert-danger">{{ session('error') }}</div> --}}
                                @endif
                                @yield('content')
                            </div>
                        </div>
                        <!-- / Content -->
                        <!-- Footer -->
                        <footer class="content-footer footer bg-footer-theme">
                            <div class="container-xxl">
                                <div
                                    class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                    <div class="mb-2 mb-md-0">
                                        ©
                                        <script>
                                            document.write(new Date().getFullYear());
                                        </script>
                                        M Rifki Adi Setiawan</a>
                                    </div>
                                </div>
                            </div>
                        </footer>
                        <!-- / Footer -->

                        {{-- <div class="content-backdrop fade"></div> --}}
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            @endif
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

    </div>

    @include('components.logout-modal')


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    @stack('scripts')
    <!-- Bootstrap 4 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>

    {{-- <!-- Bootstrap 5 Bundle (untuk komponen lain) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    {{-- <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script> --}}
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    {{-- font awesome --}}
    <script src="https://kit.fontawesome.com/87dd173a0d.js" crossorigin="anonymous"></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih",
            });
        });
    </script>
    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
