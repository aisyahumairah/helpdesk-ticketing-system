<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('dashboard') }}" class="site_title">
                <i class="fa fa-ticket-alt"></i> <span>Helpdesk</span>
            </a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h4>{{ Auth::user()->name }}</h4>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
                    </li>
                    @hasanyrole('admin|it_support')
                    <li class="{{ request()->is('support*') ? 'active' : '' }}">
                        <a><i class="fa fa-user-shield"></i> IT Support <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu" style="display: {{ request()->is('support*') ? 'block' : 'none' }}">
                            <li><a href="{{ route('support.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('support.tickets') }}">All Tickets</a></li>
                            <li><a href="{{ route('support.reports') }}">Reports</a></li>
                            <li><a href="{{ route('support.audit_trails') }}">Audit Trails</a></li>
                        </ul>
                    </li>
                    @endhasanyrole

                    @role('admin')
                    <li class="{{ request()->is('admin*') ? 'active' : '' }}">
                        <a><i class="fa fa-tools"></i> System Admin <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu" style="display: {{ request()->is('admin*') ? 'block' : 'none' }}">
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('admin.users.index') }}">User Management</a></li>
                            <li><a href="{{ route('admin.settings') }}">System Settings</a></li>
                        </ul>
                    </li>
                    @endrole
                    <li class="{{ request()->is('tickets*') ? 'active' : '' }}">
                        <a><i class="fa fa-ticket-alt"></i> Tickets <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu" style="display: {{ request()->is('tickets*') ? 'block' : 'none' }}">
                            <li><a href="{{ route('tickets.create') }}">Create New</a></li>
                            <li><a href="{{ route('tickets.index') }}">My Tickets</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <div class="menu_section">
                <h3>Admin</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-users"></i> User Management <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">Users List</a></li>
                            <li><a href="#">Roles & Permissions</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#">General Settings</a></li>
                            <li><a href="#">Email Templates</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="fa fa-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="fa fa-expand" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="fa fa-eye-slash" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="fa fa-power-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
