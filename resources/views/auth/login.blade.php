<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container">
        <div class="card">
            <!-- Logo -->
            <div class="text-center">
                <img src="{{ asset('assets/ifsp_logo_itp.png') }}" alt="Logo">
            </div>
            
<form method="POST" action="{{ route('login') }}" class="app-form" id="loginForm">
        @csrf
    <!-- Email Address -->
    <div class="input-group">
        <label for="email" class="input-label">{{ __('Email') }}</label>
        <input 
            id="email" 
            class="form-control @error('email') error @enderror" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autofocus 
            autocomplete="username"
            placeholder="Digite seu email"
        />
        <div id="email-error" class="input-error">
            @error('email')
                {{ $message }}
            @enderror
        </div>
    </div>

    <!-- Password -->
<div class="input-group">
    <label for="password" class="input-label">{{ __('Senha') }}</label>
    <div class="password-container">
        <input 
            id="password" 
            class="form-control password-input @error('password') error @enderror" 
            type="password"
            name="password"  
            autocomplete="current-password"
            placeholder="Digite sua senha"
        />
        <button type="button" class="password-toggle" id="togglePassword">
            <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2" fill="none"/>
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none"/>
            </svg>
            <svg class="eye-off-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" style="display: none;">
                <path d="m1 1 22 22" stroke="currentColor" stroke-width="2"/>
                <path d="M6.71 6.71C4.68 8.1 3 10.8 3 12c0 0 4 8 11 8 1.29 0 2.5-.24 3.57-.64" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M10.5 10.5A3 3 0 0 1 15.5 15.5" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M17.29 17.29C19.32 15.9 21 13.2 21 12c0 0-4-8-11-8-.71 0-1.38.1-2.02.27" stroke="currentColor" stroke-width="2" fill="none"/>
            </svg>
        </button>
    </div>
    <div id="password-error" class="input-error">
        @error('password')
            {{ $message }}
        @enderror
    </div>
</div>


    <!-- Remember Me -->
    <div class="form-check">
        <input id="remember_me" type="checkbox" class="form-check-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember_me">{{ __('Lembre-me') }}</label>
    </div>

    <!-- Login Button -->
    <button type="submit" class="btn-primary" id="btn-entrar">
        <span class="btn-text">{{ __('Entrar') }}</span>
        <div class="loading-spinner" style="display: none;">
            <div class="spinner"></div>
        </div>
    </button>
    
    <!-- Forgot Password Link -->
    <div id="fg-password" class="text-center">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('Esqueceu sua senha?') }}
            </a>
        @endif
    </div>
</form>

        </div>
        
    </div>
</x-guest-layout>
<script src="{{ asset('js/form-handler.js') }}"></script>

    


