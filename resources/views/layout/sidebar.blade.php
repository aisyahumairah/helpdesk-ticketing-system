<div class="col-md-3 col-sm-12 col-xs-12 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('dashboard') }}" class="site_title">
                <i class="fa fa-ticket"></i> <span>Helpdesk</span>
            </a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                    alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->name }}</h2>
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

                    <li class="{{ request()->is('tickets*') ? 'active' : '' }}">
                        <a><i class="fa fa-ticket"></i> Tickets <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu" style="display: {{ request()->is('tickets*') ? 'block' : 'none' }}">
                            <li><a href="{{ route('tickets.create') }}">Create New</a></li>
                            <li><a href="{{ route('tickets.index') }}">My Tickets</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            @hasanyrole('admin|it_support')
                <div class="menu_section">
                    <h3>IT Support</h3>
                    <ul class="nav side-menu">
                        <li class="{{ request()->is('support/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('support.dashboard') }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
                        </li>
                        <li class="{{ request()->is('support/tickets') ? 'active' : '' }}">
                            <a href="{{ route('support.tickets') }}"><i class="fa fa-list"></i> All Tickets</a>
                        </li>
                        <li class="{{ request()->is('support/reports') ? 'active' : '' }}">
                            <a href="{{ route('support.reports') }}"><i class="fa fa-chart-bar"></i> Reports</a>
                        </li>
                        <li class="{{ request()->is('support/audit*') ? 'active' : '' }}">
                            <a href="{{ route('support.audit_trails') }}"><i class="fa fa-history"></i> Audit Trails</a>
                        </li>
                    </ul>
                </div>
            @endhasanyrole

            @role('admin')
                <div class="menu_section">
                    <h3>Administration</h3>
                    <ul class="nav side-menu">
                        <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                            <a><i class="fa fa-users"></i> User Management <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu"
                                style="display: {{ request()->is('admin/users*') || request()->is('admin/roles*') ? 'block' : 'none' }}">
                                <li class="{{ request()->is('admin/users') ? 'active' : '' }}"><a
                                        href="{{ route('admin.users.index') }}">Users</a></li>
                                <li class="{{ request()->is('admin/roles*') ? 'active' : '' }}"><a
                                        href="{{ route('admin.roles.index') }}">Roles & Permissions</a></li>
                            </ul>
                        </li>
                        <li
                            class="{{ request()->is('admin/settings*') || request()->is('admin/mail-templates*') ? 'active' : '' }}">
                            <a><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu"
                                style="display: {{ request()->is('admin/settings*') || request()->is('admin/mail-templates*') ? 'block' : 'none' }}">
                                <li class="{{ request()->is('admin/settings') ? 'active' : '' }}"><a
                                        href="{{ route('admin.settings') }}">System Settings</a></li>
                                <li class="{{ request()->is('admin/mail-templates*') ? 'active' : '' }}"><a
                                        href="{{ route('admin.mail_templates.index') }}">Mail Templates</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            @endrole
        </div>
        <!-- /sidebar menu -->

    </div>
</div>
