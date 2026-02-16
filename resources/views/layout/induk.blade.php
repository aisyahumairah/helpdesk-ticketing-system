<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <!-- Compiled CSS (includes Bootstrap, Font Awesome, and all vendor styles) -->
    <script type="module" src="{{ asset('gentelella/src/main-minimal.js') }}"></script>
</head>

<body class="nav-md page-index2">
    <div class="container body">
        <div class="main_container">
            @include('layout.sidebar')
        </div>

        <!-- top navigation -->
        @include('layout.topbar')
        <!-- /top navigation -->

        <!-- page content -->
        <main class="right_col" role="main" aria-label="Main content">
            @yield('content')
        </main>
    </div>

    <!-- /page content -->

    <!-- footer content -->
    @include('layout.footer')
    <!-- /footer content -->


    @yield('script')
</body>

</html>
