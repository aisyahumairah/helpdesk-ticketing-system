<div class="top_nav">
    <div class="nav_menu d-flex align-items-center justify-content-between">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fas fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav ms-auto">
            <ul class="navbar-right d-flex align-items-center gap-3 pe-3">
                <li class="nav-item dropdown">
                    <a href="#" role="button" class="dropdown-toggle info-number" id="navbarDropdown1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end list-unstyled msg_list dropdown-menu-lg" role="menu"
                        aria-labelledby="navbarDropdown1" style="max-height: 400px; overflow-y: auto; width: 300px;">
                        @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                            <li class="nav-item">
                                <a class="dropdown-item" href="{{ route('tickets.show', $notification->data['ticket_id']) }}">
                                    <span>
                                        <strong>{{ $notification->data['message'] }}</strong>
                                        <span class="time small text-muted float-end">{{ $notification->created_at->diffForHumans() }}</span>
                                    </span>
                                    <span class="message d-block mt-1">
                                        Ticket: {{ $notification->data['ticket_code'] }}
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="nav-item text-center p-3">
                                <span class="text-muted">No new notifications</span>
                            </li>
                        @endforelse
                        
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <li class="nav-item border-top">
                                <div class="text-center p-2">
                                    <a class="dropdown-item" href="{{ route('notifications.mark_read') }}">
                                        <strong>Mark All as Read</strong>
                                        <i class="fas fa-check-double ms-1"></i>
                                    </a>
                                </div>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" role="button" class="user-profile dropdown-toggle" aria-haspopup="true"
                        id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="">{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-usermenu dropdown-menu-sm"
                        aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}" role="button"> Profile</a>
                        <a class="dropdown-item" href="#" role="button">
                            <span class="badge bg-red float-end">50%</span>
                            <span>Settings</span>
                        </a>
                        <a class="dropdown-item" href="#" role="button">Help</a>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt float-end"></i> Log Out
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
