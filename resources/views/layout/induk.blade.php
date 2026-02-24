<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery (Required for Select2/Gentelella) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Vite Assets (CSS & JS) -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="nav-md text-dark">
    <div class="container body">
        <div class="main_container">
            @include('layout.sidebar')

            <div class="right-side-container">
                @include('layout.topbar')

                <main class="right_col" role="main" aria-label="Main content">
                    @yield('content')
                </main>

                @include('layout.footer')
            </div>
        </div>
    </div>

    <!-- Modals Stack -->
    @stack('modals')

    <!-- page-specific scripts -->
    @yield('script')

    <!-- Flash Messages and Helpers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fullscreen toggle
            const fullscreenToggle = document.getElementById('fullscreen-toggle');
            if (fullscreenToggle) {
                fullscreenToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                });
            }
        });

        // Global SweetAlert2 helper functions
        window.showSuccess = function(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                confirmButtonColor: '#26B99A'
            });
        };

        window.showError = function(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                confirmButtonColor: '#E74C3C'
            });
        };

        window.showWarning = function(message) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: message,
                confirmButtonColor: '#f0ad4e'
            });
        };

        window.confirmAction = function(message, callback) {
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        };

        // Auto-show flash messages
        @if (session('success'))
            showSuccess("{{ session('success') }}");
        @endif
        @if (session('error'))
            showError("{{ session('error') }}");
        @endif
        @if (session('warning'))
            showWarning("{{ session('warning') }}");
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#E74C3C'
            });
        @endif
    </script>
</body>

</html>
