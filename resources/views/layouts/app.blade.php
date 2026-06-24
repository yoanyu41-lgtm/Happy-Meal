<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Happy Meal - Premium Online Food Delivery'))</title>
    
    <!-- Google Fonts & Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <style>
        @media (max-width: 768px) {
            .qr-sync-container {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="{{ route('products.index') }}" class="logo">
                <span class="material-icons-round" style="color: var(--primary-color);">restaurant</span>
                <span>Happy Meal</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="{{ route('products.index') }}" class="nav-link {{ (request()->routeIs('products.*') || request()->is('/')) ? 'active' : '' }}">{{ __('Products') }}</a></li>
                @auth
                    <li><a href="{{ route('customer.orders') }}" class="nav-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}">{{ __('My Orders') }}</a></li>
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('Admin Panel') }}</a></li>
                    @endif
                @endauth
            </ul>
            
            <div class="nav-actions">
                <!-- Language Switcher -->
                <div class="language-selector-container">
                    <span class="material-icons-round" style="font-size: 1.15rem; color: var(--text-secondary);">language</span>
                    <select id="language-switcher" class="font-select" onchange="window.location.href='{{ url('/lang') }}/' + this.value" aria-label="{{ __('Select Language') }}">
                        <option value="km" {{ app()->getLocale() === 'km' ? 'selected' : '' }}>ខ្មែរ (KM)</option>
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English (EN)</option>
                    </select>
                </div>

                <!-- Font Selector -->
                <div class="font-selector-container">
                    <span class="material-icons-round" style="font-size: 1.15rem; color: var(--text-secondary);">font_download</span>
                    <select id="font-switcher" class="font-select" aria-label="{{ __('Select Font') }}">
                        <option value="modern">Modern</option>
                        <option value="classic">Classic</option>
                        <option value="clean">Clean</option>
                    </select>
                </div>

                <!-- Theme Toggle Button -->
                <button class="theme-btn" id="theme-toggle" aria-label="{{ __('Toggle theme') }}">
                    <span class="material-icons-round" id="theme-icon">light_mode</span>
                </button>

                <!-- Mobile Sync QR Button -->
                <div style="position: relative;" class="qr-sync-container">
                    <button class="theme-btn" id="qr-sync-toggle" aria-label="{{ __('Open on Mobile') }}">
                        <span class="material-icons-round">qr_code</span>
                    </button>
                    <div id="qr-sync-popover" style="display: none; position: absolute; top: calc(100% + 0.5rem); right: 0; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.25rem; min-width: 220px; box-shadow: var(--card-shadow); z-index: 1000; text-align: center; width: 220px; animation: scaleIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);">
                        <span style="font-weight: 800; font-size: 0.85rem; color: var(--text-primary); display: block; margin-bottom: 0.5rem;">
                            {{ __('Scan to open on Mobile') }}
                        </span>
                        <div style="background: #fff; padding: 0.5rem; border-radius: 8px; display: inline-block; margin-bottom: 0.5rem;">
                            <img id="qr-sync-img" src="" alt="QR" style="width: 130px; height: 130px; display: block;">
                        </div>
                        <span style="font-size: 0.72rem; color: var(--text-secondary); display: block; line-height: 1.4;">
                            {{ __('Scan this QR code with your phone to continue on your mobile device.') }}
                        </span>
                    </div>
                </div>
                
                {{-- Customer Auth Button --}}
                @auth
                    <div style="position:relative;" class="desktop-auth">
                        <button style="display:flex; align-items:center; gap:0.4rem; background:rgba(99,102,241,0.1); border:1px solid rgba(99,102,241,0.25); border-radius:20px; padding:0.4rem 0.8rem; cursor:pointer; color:var(--text-primary); font-family:inherit; font-size:0.85rem; font-weight:600;">
                            <span class="material-icons-round" style="font-size:1.1rem; color:var(--primary-color);">account_circle</span>
                            {{ Str::limit(auth()->user()->name, 12) }}
                            <span class="material-icons-round" style="font-size:0.9rem;">expand_more</span>
                        </button>
                        <div style="display:none; position:absolute; top:calc(100% + 0.5rem); right:0; background:var(--bg-card); border:1px solid var(--border-color); border-radius:12px; padding:0.5rem; min-width:160px; box-shadow:0 8px 32px rgba(0,0,0,0.3); z-index:999;" class="user-dropdown">
                            <a href="{{ route('customer.profile') }}" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:8px; font-size:0.88rem; color:var(--text-primary); text-decoration:none; transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                <span class="material-icons-round" style="font-size:1rem;">manage_accounts</span>
                                {{ __('My Profile') }}
                            </a>
                            <a href="{{ route('customer.orders') }}" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:8px; font-size:0.88rem; color:var(--text-primary); text-decoration:none; transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                <span class="material-icons-round" style="font-size:1rem;">receipt_long</span>
                                {{ __('My Orders') }}
                            </a>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:8px; font-size:0.88rem; color:#ef4444; background:transparent; border:none; width:100%; cursor:pointer; font-family:inherit; transition:background 0.15s;" onmouseover="this.style.background='rgba(239,68,68,0.08)'" onmouseout="this.style.background='transparent'">
                                    <span class="material-icons-round" style="font-size:1rem;">logout</span>
                                    {{ __('Sign Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" class="desktop-auth" style="display:flex; align-items:center; gap:0.4rem; background:rgba(99,102,241,0.1); border:1px solid rgba(99,102,241,0.25); border-radius:20px; padding:0.4rem 0.8rem; color:var(--primary-color); font-size:0.85rem; font-weight:600; text-decoration:none; transition:all 0.2s;">
                        <span class="material-icons-round" style="font-size:1.05rem;">login</span>
                        {{ __('Sign In') }}
                    </a>
                @endauth

                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="cart-btn" aria-label="{{ __('Cart') }}">
                    <span class="material-icons-round">shopping_cart</span>
                    @php
                        $cart = session()->get('cart', []);
                        $cartCount = array_sum(array_column($cart, 'quantity'));
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                <!-- Hamburger Menu Button -->
                <button class="nav-toggle" id="drawer-toggle" aria-label="{{ __('Menu') }}">
                    <span class="material-icons-round">menu</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container" style="min-height: calc(100vh - 220px); padding-top: 2rem;">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success" id="success-alert">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="material-icons-round">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('success-alert').remove()" style="background:transparent; border:none; color:inherit; cursor:pointer;">
                    <span class="material-icons-round" style="font-size: 1.25rem;">close</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" id="error-alert">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="material-icons-round">error</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="document.getElementById('error-alert').remove()" style="background:transparent; border:none; color:inherit; cursor:pointer;">
                    <span class="material-icons-round" style="font-size: 1.25rem;">close</span>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} <strong>Happy Meal</strong>.</p>
        </div>
    </footer>

    <!-- Mobile Drawer Overlay -->
    <div class="drawer-overlay" id="drawer-overlay"></div>

    <!-- Mobile Drawer -->
    <div class="mobile-drawer" id="mobile-drawer">
        <div class="drawer-header">
            <a href="{{ route('products.index') }}" class="logo">
                <span class="material-icons-round" style="color: var(--primary-color);">restaurant</span>
                <span>Happy Meal</span>
            </a>
            <button class="drawer-close" id="drawer-close" aria-label="{{ __('Close') }}">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <ul class="drawer-links-list">
            <li>
                <a href="{{ route('products.index') }}" class="drawer-link {{ (request()->routeIs('products.*') || request()->is('/')) ? 'active' : '' }}">
                    <span class="material-icons-round">restaurant_menu</span>
                    <span>{{ __('Products') }}</span>
                </a>
            </li>
            @auth
                <li>
                    <a href="{{ route('customer.profile') }}" class="drawer-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                        <span class="material-icons-round">manage_accounts</span>
                        <span>{{ __('My Profile') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.orders') }}" class="drawer-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                        <span class="material-icons-round">receipt_long</span>
                        <span>{{ __('My Orders') }}</span>
                    </a>
                </li>
                @if(auth()->user()->is_admin)
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="drawer-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <span class="material-icons-round">admin_panel_settings</span>
                            <span>{{ __('Admin Panel') }}</span>
                        </a>
                    </li>
                @endif
            @endauth
        </ul>

        <div class="drawer-actions">
            {{-- Mobile Language Selector --}}
            <div class="language-selector-container">
                <div style="display:flex; align-items:center; gap:0.35rem;">
                    <span class="material-icons-round" style="font-size: 1.15rem; color: var(--text-secondary);">language</span>
                    <span style="font-size:0.85rem; font-weight:600; color:var(--text-secondary);">{{ __('Language') }}</span>
                </div>
                <select id="mobile-language-switcher" class="font-select" onchange="window.location.href='{{ url('/lang') }}/' + this.value" aria-label="{{ __('Select Language') }}">
                    <option value="km" {{ app()->getLocale() === 'km' ? 'selected' : '' }}>ខ្មែរ (KM)</option>
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English (EN)</option>
                </select>
            </div>

            {{-- Mobile Font Selector --}}
            <div class="font-selector-container">
                <div style="display:flex; align-items:center; gap:0.35rem;">
                    <span class="material-icons-round" style="font-size: 1.15rem; color: var(--text-secondary);">font_download</span>
                    <span style="font-size:0.85rem; font-weight:600; color:var(--text-secondary);">{{ __('Font') }}</span>
                </div>
                <select id="mobile-font-switcher" class="font-select" aria-label="{{ __('Select Font') }}">
                    <option value="modern">Modern</option>
                    <option value="classic">Classic</option>
                    <option value="clean">Clean</option>
                </select>
            </div>

            {{-- Mobile Auth Actions --}}
            @auth
                <div style="border-top:1px solid var(--border-color); padding-top:1rem; margin-top:0.5rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1rem; padding:0 0.5rem;">
                        <span class="material-icons-round" style="font-size:1.5rem; color:var(--primary-color);">account_circle</span>
                        <div style="font-weight:700; font-size:0.95rem;">{{ auth()->user()->name }}</div>
                    </div>
                    <form method="POST" action="{{ route('customer.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="width:100%; display:flex; align-items:center; justify-content:center; gap:0.5rem; color:#ef4444; border-color:rgba(239,68,68,0.25); background:rgba(239,68,68,0.04);">
                            <span class="material-icons-round" style="font-size:1.15rem;">logout</span>
                            <span>{{ __('Sign Out') }}</span>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('customer.login') }}" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">
                    <span class="material-icons-round">login</span>
                    <span>{{ __('Sign In') }}</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- Dark/Light Theme & Font Switcher Script -->
    <script>
        // Toggle and close QR Sync Popover
        document.addEventListener('click', function(e) {
            const popover = document.getElementById('qr-sync-popover');
            const toggleBtn = document.getElementById('qr-sync-toggle');
            if (popover && toggleBtn) {
                if (toggleBtn.contains(e.target)) {
                    const isOpen = popover.style.display !== 'none';
                    if (isOpen) {
                        popover.style.display = 'none';
                    } else {
                        const qrImg = document.getElementById('qr-sync-img');
                        if (qrImg) {
                            qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(window.location.href);
                        }
                        popover.style.display = 'block';
                    }
                } else if (!popover.contains(e.target)) {
                    popover.style.display = 'none';
                }
            }
        });

        // Toggle and close user dropdown
        document.addEventListener('click', function(e) {
            const dropdowns = document.querySelectorAll('.user-dropdown');
            dropdowns.forEach(d => {
                const btn = d.previousElementSibling;
                if (btn && btn.contains(e.target)) {
                    const isOpen = d.style.display !== 'none';
                    if (isOpen) {
                        d.style.display = 'none';
                        d.classList.remove('open');
                    } else {
                        d.style.display = 'block';
                        d.classList.add('open');
                    }
                } else if (!d.contains(e.target)) {
                    d.style.display = 'none';
                    d.classList.remove('open');
                }
            });
        });

        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const fontSwitcher = document.getElementById('font-switcher');
        const htmlElement = document.documentElement;
        
        // Load theme preference
        const savedTheme = localStorage.getItem('theme') || 'dark';
        htmlElement.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
        
        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.textContent = 'light_mode';
            } else {
                themeIcon.textContent = 'dark_mode';
            }
        }

        // Load font preference
        const savedFont = localStorage.getItem('theme-font') || 'modern';
        htmlElement.setAttribute('data-font', savedFont);
        if (fontSwitcher) {
            fontSwitcher.value = savedFont;
            
            fontSwitcher.addEventListener('change', (e) => {
                const selectedFont = e.target.value;
                htmlElement.setAttribute('data-font', selectedFont);
                localStorage.setItem('theme-font', selectedFont);
            });
        }

        // Mobile Drawer JavaScript
        const drawerToggle = document.getElementById('drawer-toggle');
        const drawerClose = document.getElementById('drawer-close');
        const drawerOverlay = document.getElementById('drawer-overlay');
        const mobileDrawer = document.getElementById('mobile-drawer');

        function openDrawer() {
            mobileDrawer.classList.add('open');
            drawerOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            mobileDrawer.classList.remove('open');
            drawerOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (drawerToggle) drawerToggle.addEventListener('click', openDrawer);
        if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
        if (drawerOverlay) drawerOverlay.addEventListener('click', closeDrawer);

        // Sync Font Switcher in Mobile Drawer
        const mobileFontSwitcher = document.getElementById('mobile-font-switcher');
        if (mobileFontSwitcher) {
            mobileFontSwitcher.value = savedFont;
            mobileFontSwitcher.addEventListener('change', (e) => {
                const selectedFont = e.target.value;
                htmlElement.setAttribute('data-font', selectedFont);
                localStorage.setItem('theme-font', selectedFont);
                if (fontSwitcher) fontSwitcher.value = selectedFont;
            });
            if (fontSwitcher) {
                fontSwitcher.addEventListener('change', (e) => {
                    mobileFontSwitcher.value = e.target.value;
                });
            }
        }

        // Auto remove alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
        }, 5000);
    </script>
    @yield('scripts')
</body>
</html>
