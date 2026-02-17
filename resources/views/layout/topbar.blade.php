<div class="top_nav">
    <div class="nav_menu d-flex align-items-center justify-content-between">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav ms-auto">
            <ul class="navbar-right d-flex align-items-center gap-3 pe-3">

                <li role="presentation" class="nav-item dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="badge bg-green">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end list-unstyled msg_list pb-0" role="menu" aria-labelledby="navbarDropdown1" style="min-width: 300px;">
                        @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                            <li class="nav-item">
                                <a class="dropdown-item" href="{{ route('tickets.show', $notification->data['ticket_id']) }}">
                                    <span class="image"><img src="https://ui-avatars.com/api/?name=System&background=random" alt="Profile Image" /></span>
                                    <span>
                                        <span>{{ $notification->data['message'] }}</span>
                                        <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                                    </span>
                                    <span class="message text-truncate d-block">
                                        Ticket: {{ $notification->data['ticket_code'] }}
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="nav-item">
                                <div class="text-center p-3">
                                    <span class="text-muted">No new notifications</span>
                                </div>
                            </li>
                        @endforelse

                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <li class="nav-item border-top">
                                <div class="text-center p-2">
                                    <a class="dropdown-item" href="{{ route('notifications.mark_read') }}">
                                        <strong>Mark All as Read</strong>
                                        <i class="fa fa-angle-right ms-1"></i>
                                    </a>
                                </div>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="">{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-usermenu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}"><i class="fa fa-user pull-right"></i> Profile</a>
                        <a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="fa fa-cog pull-right"></i> Settings</a>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out-alt pull-right"></i> Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
