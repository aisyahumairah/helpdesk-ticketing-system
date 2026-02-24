<div class="top_nav">
    <div class="nav_menu d-flex align-items-center justify-content-between">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav ms-auto">
            <ul class="navbar-right d-flex align-items-center gap-3 pe-3">

                <li role="presentation" class="nav-item dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="badge bg-green">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end list-unstyled shadow border-0 py-0" role="menu"
                        aria-labelledby="navbarDropdown1" style="width: 350px; max-width: 90vw; overflow: hidden;">
                        <li class="nav-item bg-light p-2 border-bottom">
                            <h6 class="dropdown-header p-0 ps-2">Recent Notifications</h6>
                        </li>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse(Auth::user()->unreadNotifications->take(10) as $notification)
                                <li class="nav-item border-bottom">
                                    <a class="dropdown-item d-flex align-items-start gap-3 p-3 text-wrap"
                                        href="{{ route('support.adminshow', $notification->data['ticket_id']) }}"
                                        style="white-space: normal; background: transparent;">
                                        <div class="flex-shrink-0 pt-1">
                                            <div class="rounded-circle bg-info d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px;">
                                                <i class="fa fa-envelope-o"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <span class="fw-bold text-dark small">New Update</span>
                                                <span class="text-muted"
                                                    style="font-size: 0.7rem; white-space: nowrap;">
                                                    <i class="fa fa-clock ps-1"></i>
                                                    {{ $notification->created_at->diffForHumans(null, true) }}
                                                </span>
                                            </div>
                                            <div class="text-secondary mt-1 overflow-hidden"
                                                style="font-size: 0.85rem; line-height: 1.4; overflow-wrap: break-word;">
                                                {{ $notification->data['message'] }}
                                            </div>
                                            <div class="text-primary mt-1 fw-semibold" style="font-size: 0.75rem;">
                                                #{{ $notification->data['ticket_code'] }}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="nav-item">
                                    <div class="text-center p-4">
                                        <i class="fa fa-bell-slash text-muted d-block mb-2"
                                            style="font-size: 2rem;"></i>
                                        <span class="text-muted">No new notifications</span>
                                    </div>
                                </li>
                            @endforelse
                        </div>

                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <li class="nav-item">
                                <a class="dropdown-item text-center p-3 text-primary fw-bold"
                                    href="{{ route('notifications.mark_read') }}">
                                    <span>Mark All as Read</span>
                                    <i class="fa fa-check-circle ms-1"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                @canany(['user.read', 'role.read', 'settings.read', 'mail_template.read'])
                    <li class="nav-item dropdown">
                        <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-usermenu" aria-labelledby="navbarDropdown">
                            @canany(['user.read'])
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                    <i class="fa fa-users pull-right"></i>
                                    Manage Users
                                </a>
                            @endcanany
                            @canany(['role.read'])
                                <a class="dropdown-item" href="{{ route('admin.roles.index') }}">
                                    <i class="fa fa-shield pull-right"></i>
                                    Manage Roles
                                </a>
                            @endcanany
                            @canany(['permission.read'])
                                <a class="dropdown-item" href="{{ route('admin.permissions.index') }}">
                                    <i class="fa fa-key pull-right"></i>
                                    Manage Permissions
                                </a>
                            @endcanany
                            @canany(['settings.read'])
                                <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                    <i class="fa fa-gear pull-right"></i>
                                    System Settings
                                </a>
                            @endcanany
                            @canany(['mail_template.read'])
                                <a class="dropdown-item" href="{{ route('admin.mail_templates.index') }}">
                                    <i class="fa fa-envelope pull-right"></i>
                                    Mail Templates
                                </a>
                            @endcanany
                            @canany(['audit_trail.read'])
                                <a class="dropdown-item" href="{{ route('support.audit_trails') }}">
                                    <i class="fa fa-history pull-right"></i>
                                    Audit Trail
                                </a>
                            @endcanany
                        </div>
                    </li>
                @endcanany

                <li class="nav-item dropdown">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                            alt="">{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-usermenu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}"><i class="fa fa-user pull-right"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="{{ route('password.change') }}"><i
                                class="fa fa-key pull-right"></i>
                            Change Password
                        </a>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="fa fa-sign-out-alt pull-right"></i> Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
