<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <!-- Compiled CSS (Bootstrap 5, Icons, Font Awesome) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom styling to mimic Gentelella while unbuilt -->
    <style>
        body { background: #2A3F54; color: #73879C; font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif; font-size: 13px; font-weight: 400; line-height: 1.471; margin: 0; padding: 0; }
        .container.body { position: relative; width: 100%; display: flex; }
        .main_container { background: #2A3F54; width: 100%; position: relative; }
        
        /* Badges */
        .badge { padding: 5px 10px; border-radius: 4px; font-weight: 600; font-size: 11px; }
        .badge-primary { background-color: #337ab7; color: #fff; }
        .badge-success { background-color: #26B99A; color: #fff; }
        .badge-info { background-color: #3498DB; color: #fff; }
        .badge-warning { background-color: #f0ad4e; color: #fff; }
        .badge-danger { background-color: #E74C3C; color: #fff; }
        .badge-dark { background-color: #34495E; color: #fff; }
        
        /* Sidebar */
        .left_col { background: #2A3F54; width: 230px; padding: 0; position: fixed; height: 100vh; z-index: 100; overflow-y: auto; }
        .nav-md .left_col { width: 230px; }
        .nav_title { background: #2A3F54; width: 100%; height: 57px; line-height: 57px; display: block; }
        .site_title { font-weight: 400; font-size: 22px; width: 100%; color: #ECF0F1 !important; line-height: 59px; display: block; height: 55px; margin: 0; padding-left: 20px; text-decoration: none; }
        .profile { padding: 25px 10px 10px; display: flex; align-items: center; clear: both; }
        .profile_pic { width: 35%; }
        .profile_img { width: 100%; background: #fff; border: 1px solid rgba(52,73,94,0.44); padding: 4px; border-radius: 50%; }
        .profile_info { width: 65%; padding-left: 10px; }
        .profile_info h4 { font-size: 14px; color: #ECF0F1; margin: 0; font-weight: 500; }
        .profile_info span { font-size: 13px; color: #BAB8B8; }
        
        .menu_section h3 { padding-left: 23px; color: #fff; text-transform: uppercase; letter-spacing: .5px; font-weight: bold; font-size: 12px; margin-bottom: 5px; margin-top: 20px; }
        .nav.side-menu > li > a { position: relative; display: block; padding: 13px 15px 12px; color: #E7E7E7; font-weight: 500; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .nav.side-menu > li > a:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .nav.side-menu > li.active > a { background: linear-gradient(#334556, #2C4257), #2A3F54; box-shadow: rgba(0,0,0,0.25) 0 1px 0, inset rgba(255,255,255,0.16) 0 1px 0; color: #fff; }
        .nav.side-menu > li > a i { font-size: 18px; text-align: center; width: 26px; display: inline-block; vertical-align: middle; margin-right: 5px; }
        
        .nav.child_menu { list-style: none; padding-left: 0; background: rgba(255,255,255,0.05); display: none; }
        .nav.child_menu li a { font-size: 12px; color: rgba(255,255,255,0.75); padding: 9px 35px; display: block; text-decoration: none; transition: 0.3s; }
        .nav.child_menu li a:hover { color: #fff; background: rgba(255,255,255,0.1); }
        
        .sidebar-footer { background: #2A3F54; position: fixed; width: 230px; bottom: 0; left: 0; display: flex; border-top: 1px solid rgba(255,255,255,0.1); }
        .sidebar-footer a { width: 25%; padding: 10px 0; text-align: center; color: #5A738E; font-size: 14px; }
        .sidebar-footer a:hover { background: #425567; color: #fff; }
        
        /* Top Navigation */
        .top_nav { margin-left: 230px; background: #EDEDED; height: 57px; width: calc(100% - 230px); border-bottom: 1px solid #D9DEE4; position: sticky; top: 0; z-index: 99; }
        .nav_menu { height: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; }
        .toggle { width: 26px; font-size: 20px; cursor: pointer; }
        
        /* Main Content */
        .right_col { background: #F7F7F7; padding: 20px; margin-left: 230px; min-height: calc(100vh - 107px); width: calc(100% - 230px); }
        
        /* Footer */
        footer { margin-left: 230px; background: #fff; padding: 15px 20px; border-top: 1px solid #D9DEE4; width: calc(100% - 230px); }

        /* Panels */
        .x_panel { position: relative; width: 100%; margin-bottom: 10px; padding: 10px 17px; background: #fff; border: 1px solid #E6E9ED; border-radius: 3px; display: inline-block; }
        .x_title { border-bottom: 2px solid #E6E9ED; padding: 1px 5px 6px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .x_title h2 { margin: 5px 0; font-size: 18px; font-weight: 400; color: #73879C; }
        .x_content { padding: 0 5px 6px; position: relative; width: 100%; float: left; clear: both; margin-top: 5px; }

        /* Forms */
        .form-group { margin-bottom: 1rem; }
        .label-align { padding-top: 8px; text-align: right; }
        .well { min-height: 20px; padding: 19px; margin-bottom: 20px; background-color: #f5f5f5; border: 1px solid #e3e3e3; border-radius: 4px; box-shadow: inset 0 1px 1px rgba(0,0,0,.05); }

        /* Responsive */
        @media (max-width: 991px) {
            .left_col { display: none; }
            .top_nav, .right_col, footer { margin-left: 0; width: 100%; }
            .label-align { text-align: left; }
        }
    </style>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- /page content -->
    <script type="module" src="{{ asset('gentelella/src/main-minimal.js') }}"></script>
    <!-- Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            @include('layout.sidebar')
            @include('layout.topbar')
            
            <main class="right_col" role="main" aria-label="Main content">
                @yield('content')
            </main>
            
            @include('layout.footer')
        </div>
    </div>

    @yield('script')

    <!-- Simple Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle submenu
            const menuLinks = document.querySelectorAll('.side-menu > li > a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const parent = this.parentElement;
                    const childMenu = parent.querySelector('.child_menu');
                    if (childMenu) {
                        e.preventDefault();
                        const isVisible = childMenu.style.display === 'block';
                        // Close other menus
                        document.querySelectorAll('.child_menu').forEach(menu => menu.style.display = 'none');
                        document.querySelectorAll('.side-menu > li').forEach(li => li.classList.remove('active'));
                        
                        if (!isVisible) {
                            childMenu.style.display = 'block';
                            parent.classList.add('active');
                        }
                    }
                });
            });

            // Handle hover/active states for sub-items
            const subLinks = document.querySelectorAll('.child_menu li a');
            subLinks.forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelectorAll('.child_menu li').forEach(li => li.classList.remove('active'));
                    this.parentElement.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>
