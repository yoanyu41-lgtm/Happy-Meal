@extends('layouts.app')

@section('title', __('Sign In') . ' — Happy Meal')

@section('content')
<div style="max-width: 420px; margin: 3rem auto;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 2.5rem 2rem; box-shadow: var(--card-shadow);">

        {{-- Header --}}
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-color), #8b5cf6); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <span class="material-icons-round" style="color: #fff; font-size: 1.75rem;">login</span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; letter-spacing: -0.5px;">{{ __('Welcome Back!') }}</h1>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.3rem;">{{ __('Sign in to view your orders') }}</p>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.25rem; font-size: 0.85rem; color: #fca5a5;">
                @foreach ($errors->all() as $error)
                    <div style="display:flex; gap:0.4rem; align-items:center;">
                        <span class="material-icons-round" style="font-size:0.9rem;">error</span>{{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('customer.login.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('Email Address *') }}</label>
                <input type="email" name="email" class="form-control"
                    placeholder="{{ __('e.g. example@domain.com') }}"
                    value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 0.5rem;">
                <label class="form-label">{{ __('Password *') }}</label>
                <input type="password" name="password" class="form-control"
                    placeholder="••••••••" required>
            </div>

            <label class="remember-row">
                <input type="checkbox" name="remember" value="1">
                {{ __('Remember me') }}
            </label>

            <button type="submit" class="btn btn-primary" style="width:100%; padding: 0.9rem; font-size: 1rem; font-weight: 700;">
                <span class="material-icons-round">login</span>
                {{ __('Sign In') }}
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.25rem; font-size: 0.88rem; color: var(--text-secondary);">
            {{ __("Don't have an account?") }}
            <a href="{{ route('customer.register') }}" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
                {{ __('Create Account') }}
            </a>
        </div>

    </div>
</div>
@endsection
