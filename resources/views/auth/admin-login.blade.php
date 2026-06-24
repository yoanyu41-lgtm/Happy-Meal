<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin Login') }} — Happy Meal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Kantumruuy+Pro:wght@400;500;600;700&family=Playfair+Display:wght@400;600;700;800&family=Hanuman:wght@400;700&family=Quicksand:wght@400;600;700&family=Nokora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <script>
        // Load theme and font preferences early to prevent layout shift
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
        const savedFont = localStorage.getItem('theme-font') || 'modern';
        document.documentElement.setAttribute('data-font', savedFont);
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* Dynamic Font Selectors */
        html {
            --font-modern: 'Outfit', 'Kantumruuy Pro', sans-serif;
            --font-classic: 'Playfair Display', 'Hanuman', serif;
            --font-clean: 'Quicksand', 'Nokora', sans-serif;
            
            --font-primary: var(--font-modern); /* Default */
        }

        html[data-font="modern"] {
            --font-primary: var(--font-modern);
        }

        html[data-font="classic"] {
            --font-primary: var(--font-classic);
        }

        html[data-font="clean"] {
            --font-primary: var(--font-clean);
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg: #070714;
            --card: rgba(18, 20, 42, 0.65);
            --border: rgba(99,102,241,0.25);
            --text: #f0f0ff;
            --muted: #8b8db8;
            --danger: #ef4444;
            --success: #10b981;
        }

        body {
            font-family: var(--font-primary);
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Floating Glows background */
        body::before {
            content: '';
            position: fixed;
            top: 15%;
            left: 50%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            animation: float-glow-1 12s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -10%;
            right: 10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 75%);
            pointer-events: none;
            z-index: 0;
            animation: float-glow-2 16s ease-in-out infinite;
        }

        @keyframes float-glow-1 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.15; }
            50% { transform: translate(-30%, -40%) scale(1.1); opacity: 0.22; }
        }

        @keyframes float-glow-2 {
            0%, 100% { transform: translate(20%, 20%) scale(1); opacity: 0.12; }
            50% { transform: translate(10%, 10%) scale(1.15); opacity: 0.18; }
        }

        .login-card {
            background: var(--card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            box-shadow: 
                0 24px 80px rgba(0, 0, 0, 0.6), 
                0 0 0 1px rgba(255, 255, 255, 0.05) inset,
                0 0 30px rgba(99, 102, 241, 0.1);
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            justify-content: center;
            margin-bottom: 2.25rem;
        }

        .login-logo-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 30px rgba(99,102,241,0.45);
            animation: pulse-glow 3s infinite alternate;
        }

        .login-logo-icon .material-icons-round { color: #fff; font-size: 1.75rem; }

        @keyframes pulse-glow {
            0% { box-shadow: 0 8px 24px rgba(99,102,241,0.4); transform: scale(1); }
            100% { box-shadow: 0 8px 36px rgba(99,102,241,0.65), 0 0 15px rgba(139,92,246,0.3); transform: scale(1.03); }
        }

        .login-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 60%, var(--muted) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-title p {
            font-size: 0.88rem;
            color: var(--muted);
            margin-top: 0.4rem;
        }

        .form-group { margin-bottom: 1.35rem; }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--muted);
            margin-bottom: 0.6rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 1.1rem;
            transition: all 0.25s ease;
            pointer-events: none;
        }

        .input-wrap:focus-within .input-icon {
            color: var(--primary);
            transform: translateY(-50%) scale(1.05);
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            color: var(--text);
            font-family: var(--font-primary);
            font-size: 0.95rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15), 0 0 20px rgba(99, 102, 241, 0.2);
        }

        .form-control::placeholder { color: rgba(255, 255, 255, 0.25); }

        /* Webkit Autofill Style Fixes */
        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover, 
        .form-control:-webkit-autofill:focus, 
        .form-control:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #12142a inset !important;
            -webkit-text-fill-color: var(--text) !important;
            border-color: rgba(99, 102, 241, 0.3) !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .remember-row {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.75rem;
            font-size: 0.88rem;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
            transition: color 0.2s ease;
        }

        .remember-row:hover {
            color: var(--text);
        }

        .remember-row input[type=checkbox] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.03);
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            outline: none;
        }

        .remember-row input[type=checkbox]:checked {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.4);
        }

        .remember-row input[type=checkbox]:checked::after {
            content: '✔';
            font-size: 0.7rem;
            font-weight: 900;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .remember-row input[type=checkbox]:focus-visible {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 0.95rem;
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-family: var(--font-primary);
            font-size: 0.98rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(99, 102, 241, 0.55), 0 0 10px rgba(124,58,237,0.25);
        }

        .btn-login:active {
            transform: translateY(0) scale(0.98);
        }

        .error-box {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
            color: #fca5a5;
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .back-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .back-link a:hover {
            color: #8b5cf6;
            text-decoration: none;
        }

        /* ── Light Mode Overrides ── */
        html[data-theme="light"] {
            --bg: #fafaf9;
            --card: rgba(255, 255, 255, 0.85);
            --border: rgba(0, 0, 0, 0.08);
            --text: #1c1917;
            --muted: #78716c;
            --bg-autofill: #ffffff;
        }

        html[data-theme="light"] body::before,
        html[data-theme="light"] body::after {
            opacity: 0.05;
        }

        html[data-theme="light"] .form-control {
            background: rgba(0, 0, 0, 0.02);
            border-color: rgba(0, 0, 0, 0.08);
            color: #1c1917;
        }

        html[data-theme="light"] .form-control:focus {
            background: rgba(99, 102, 241, 0.02);
        }

        html[data-theme="light"] .form-control::placeholder {
            color: rgba(0, 0, 0, 0.35);
        }

        html[data-theme="light"] .form-control:-webkit-autofill,
        html[data-theme="light"] .form-control:-webkit-autofill:hover, 
        html[data-theme="light"] .form-control:-webkit-autofill:focus, 
        html[data-theme="light"] .form-control:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #ffffff inset !important;
            -webkit-text-fill-color: #1c1917 !important;
            border-color: rgba(99, 102, 241, 0.3) !important;
        }

        html[data-theme="light"] .remember-row input[type=checkbox] {
            border-color: rgba(0, 0, 0, 0.15);
            background: rgba(0, 0, 0, 0.03);
        }

        html[data-theme="light"] .error-box {
            background: rgba(239, 68, 68, 0.05);
            border-color: rgba(239, 68, 68, 0.2);
            color: #b91c1c;
        }

        html[data-theme="light"] .login-title h1 {
            background: linear-gradient(135deg, #1c1917 60%, #78716c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        html[data-theme="light"] .login-card {
            box-shadow: 
                0 24px 80px rgba(28, 25, 23, 0.08), 
                0 0 0 1px rgba(0, 0, 0, 0.03),
                0 0 30px rgba(99, 102, 241, 0.03);
        }

        /* ── Language Selector ── */
        .language-selector-container {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            background: var(--card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 0.25rem 0.5rem 0.25rem 0.75rem;
            height: 42px;
            transition: all 0.25s ease;
        }

        .language-selector-container:hover {
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(99,102,241,0.2);
        }

        .font-select {
            background: transparent;
            border: none;
            color: var(--text);
            font-family: var(--font-primary);
            font-size: 0.85rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
            padding-right: 0.25rem;
        }

        .font-select option {
            background-color: var(--bg);
            color: var(--text);
        }
    </style>
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-selector-container">
        <span class="material-icons-round" style="font-size: 1.15rem; color: var(--muted);">language</span>
        <select id="language-switcher" class="font-select" onchange="window.location.href='{{ url('/lang') }}/' + this.value" aria-label="{{ __('Select Language') }}">
            <option value="km" {{ app()->getLocale() === 'km' ? 'selected' : '' }}>ខ្មែរ (KM)</option>
            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English (EN)</option>
        </select>
    </div>

    <!-- Theme Toggle Button -->
    <button class="theme-btn" id="theme-toggle" aria-label="Toggle theme" style="
        position: absolute; top: 1.5rem; right: 1.5rem; z-index: 100;
        background: transparent; border: 1px solid var(--border);
        color: var(--text); width: 42px; height: 42px; border-radius: 50%;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all 0.25s ease;
    " onmouseover="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 10px rgba(99,102,241,0.2)';" onmouseout="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
        <span class="material-icons-round" id="theme-icon">light_mode</span>
    </button>

    <div class="login-card">
        {{-- Logo --}}
        <div class="login-logo">
            <div class="login-logo-icon">
                <span class="material-icons-round">restaurant</span>
            </div>
        </div>

        {{-- Title --}}
        <div class="login-title">
            <h1>{{ __('Admin Login') }}</h1>
            <p>{{ __('Sign in to manage Happy Meal') }}</p>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="error-box">
                <span class="material-icons-round" style="font-size:1rem; margin-top:0.1rem; flex-shrink:0;">error</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Session Error --}}
        @if (session('error'))
            <div class="error-box">
                <span class="material-icons-round" style="font-size:1rem; margin-top:0.1rem;">error</span>
                {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('Email Address') }}</label>
                <div class="input-wrap">
                    <span class="material-icons-round input-icon">email</span>
                    <input type="email" name="email" class="form-control"
                        placeholder="admin@happymeal.com"
                        value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('Password') }}</label>
                <div class="input-wrap">
                    <span class="material-icons-round input-icon">lock</span>
                    <input type="password" name="password" class="form-control"
                        placeholder="••••••••" required>
                </div>
            </div>

            <label class="remember-row">
                <input type="checkbox" name="remember" value="1">
                {{ __('Remember me') }}
            </label>

            <button type="submit" class="btn-login">
                <span class="material-icons-round">login</span>
                {{ __('Sign In to Dashboard') }}
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('products.index') }}">
                ← {{ __('Back to Store') }}
            </a>
        </div>
    </div>

    <!-- Theme Toggle Script -->
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const htmlElement = document.documentElement;

        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.textContent = 'light_mode';
            } else {
                themeIcon.textContent = 'dark_mode';
            }
        }

        const savedTheme = localStorage.getItem('theme') || 'dark';
        updateThemeIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    </script>
</body>
</html>
