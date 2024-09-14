<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            <img src="{{ asset('assets/images/logo.png') }}" alt="">
        </a>
        {{-- <a href="#" class="sidebar-brand" style='font-size:15px;'>
            @yield('title', 'Land Management')<span></span>
        </a> --}}
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                    <i class="link-icon" data-lucide="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            {{--
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ url('/admin/users') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Users</span>
                </a>
            </li> --}}
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ url('/admin/change-password') }}" class="nav-link">
                    <i class="link-icon" data-lucide="key"></i>
                    <span class="link-title">Change Password</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ url('/admin/admins') }}" class="nav-link">
                    <i class="link-icon" data-lucide="user-plus"></i>
                    <span class="link-title">Admins</span>
                </a>
            </li>
            <li class="nav-item nav-category">Land</li>
            {{-- <li class="nav-item {{ active_class(['email/*']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#email" role="button"
                    aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">
                    <i class="link-icon" data-lucide="mail"></i>
                    <span class="link-title">Email</span>
                    <i class="link-arrow" data-lucide="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['email/*']) }}" id="email">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ url('/email/inbox') }}"
                                class="nav-link {{ active_class(['email/inbox']) }}">Inbox</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/email/read') }}"
                                class="nav-link {{ active_class(['email/read']) }}">Read</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/email/compose') }}"
                                class="nav-link {{ active_class(['email/compose']) }}">Compose</a>
                        </li>
                    </ul>
                </div>
            </li> --}}
            <li class="nav-item {{ active_class(['apps/chat']) }}">
                <a href="{{ url('/admin/land') }}" class="nav-link">
                    <i class="link-icon" data-lucide="land-plot"></i>
                    <span class="link-title">Land Info</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['apps/calendar']) }}">
                <a href="{{ url('/admin/land-distribution') }}" class="nav-link">
                    <i class="link-icon" data-lucide="land-plot"></i>
                    <span class="link-title">Land Destribution</span>
                </a>
            </li>
            <li class="nav-item nav-category">Users</li>
            <li class="nav-item {{ active_class(['apps/calendar']) }}">
                <a href="{{ url('/admin/member') }}" class="nav-link">
                    <i class="link-icon" data-lucide="users"></i>
                    <span class="link-title">Members</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['apps/calendar']) }}">
                <a href="{{ url('/admin/members-land') }}" class="nav-link">
                    <i class="link-icon" data-lucide="users"></i>
                    <span class="link-title">Members Land Upload</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['apps/calendar']) }}">
                <a href="{{ url('/admin/add-agent') }}" class="nav-link">
                    <i class="link-icon" data-lucide="users"></i>
                    <span class="link-title">Agents</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/agent-land') }}" class="nav-link">
                    <i class="link-icon" data-lucide="file-minus"></i>
                    <span class="link-title">Agent Distribution</span>
                </a>
            </li>


            <li class="nav-item nav-category">Payment</li>
            <li class="nav-item {{ active_class(['apps/calendar']) }}">
                <a href="{{ url('/admin/member-payment/showform') }}" class="nav-link">
                    <i class="link-icon" data-lucide="dollar-sign"></i>
                    <span class="link-title">Members Payment</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/agent-payment-add') }}" target="_blank" class="nav-link">
                    <i class="link-icon" data-lucide="dollar-sign"></i>
                    <span class="link-title">Agents Payment</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.consultant') }}" class="nav-link">
                    <i class="link-icon" data-lucide="dollar-sign"></i>
                    <span class="link-title">Pay Consultant</span>
                </a>
            </li>
            {{-- <li class="nav-item {{ active_class(['apps/calendar']) }}">
                <a href="{{ url('/apps/calendar') }}" class="nav-link">
                    <i class="link-icon" data-lucide="dollar-sign"></i>
                    <span class="link-title">Refunds</span>
                </a>
            </li> --}}

            <li class="nav-item nav-category">Reports</li>
            <li class="nav-item">
                <a href="{{ url('/admin/Land-report') }}" class="nav-link">
                    <i class="link-icon" data-lucide="file-minus"></i>
                    <span class="link-title">Payments Per Land</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/admin/member-report') }}" class="nav-link">
                    <i class="link-icon" data-lucide="file-minus"></i>
                    <span class="link-title">Payments Per Member</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ url('/admin/agent-report') }}" class="nav-link">
                    <i class="link-icon" data-lucide="file-minus"></i>
                    <span class="link-title">Agents Commision</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.logout') }}" class="nav-link">
                    <i class="link-icon" data-lucide="log-out"></i>
                    <span class="link-title">Logout</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
<nav class="settings-sidebar">
    <div class="sidebar-body">
        <a href="#" class="settings-sidebar-toggler">
            <i data-lucide="settings"></i>
        </a>
        <h6 class="text-muted mb-2">Sidebar:</h6>
        <div class="mb-3 pb-3 border-bottom">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight"
                        value="sidebar-light" checked>
                    Light
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark"
                        value="sidebar-dark">
                    Dark
                </label>
            </div>
        </div>
        {{-- <div class="theme-wrapper">
            <h6 class="text-muted mb-2">Light Version:</h6>
            <a class="theme-item active" href="">
                <img src="{{ url('assets/images/screenshots/light.jpg') }}" alt="light version">
            </a>
            <h6 class="text-muted mb-2">Dark Version:</h6>
            <a class="theme-item" href="">
                <img src="{{ url('assets/images/screenshots/dark.jpg') }}" alt="light version">
            </a>
        </div> --}}
    </div>
</nav>
