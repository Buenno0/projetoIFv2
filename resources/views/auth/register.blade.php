<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<x-guest-layout>
    <div class="container">
        <div class="card">
            <!-- Logo -->
            <div class="text-center">
                <img src="{{ asset('assets/ifsp_logo_itp.png') }}" alt="Logo">
            </div>

            <form method="POST" action="{{ route('register') }}" class="app-form" id="registerForm">
                @csrf

                <!-- Nome -->
                <div class="input-group">
                    <label for="name" class="input-label">{{ __('Nome') }}</label>
                    <input id="name" 
                           class="form-control @error('name') error @enderror" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                            autofocus autocomplete="name"
                           placeholder="Digite seu nome">
                    <div id="name-error" class="input-error">
                        @error('name') {{ $message }} @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="email" class="input-label">{{ __('Email') }}</label>
                    <input id="email" 
                           class="form-control @error('email') error @enderror" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           autocomplete="username"
                           placeholder="Digite seu email">
                    <div id="email-error" class="input-error">
                        @error('email') {{ $message }} @enderror
                    </div>
                </div>

                <!-- Senha -->
                <div class="input-group">
                    <label for="password" class="input-label">{{ __('Senha') }}</label>
                    <div class="password-container">
                        <input id="password" 
                               class="form-control password-input @error('password') error @enderror" 
                               type="password"
                               name="password" 
                               autocomplete="new-password"
                               placeholder="Digite sua senha">

                        <!-- Botão de toggle de senha no padrão do login -->
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
                        @error('password') {{ $message }} @enderror
                    </div>
                </div>

                <!-- Confirmar Senha -->
                <div class="input-group">
                    <label for="password_confirmation" class="input-label">{{ __('Confirmar Senha') }}</label>
                    <input id="password_confirmation" 
                           class="form-control @error('password_confirmation') error @enderror" 
                           type="password"
                           name="password_confirmation" 
                           autocomplete="new-password"
                           placeholder="Confirme sua senha">
                    <div id="password_confirmation-error" class="input-error">
                        @error('password_confirmation') {{ $message }} @enderror
                    </div>
                </div>

                <!-- Botão Registrar -->
                <button type="submit" class="btn-primary" id="btn-entrar">
                    <span class="btn-text">{{ __('Registrar') }}</span>
                    <div class="loading-spinner" style="display: none;">
                        <div class="spinner"></div>
                    </div>
                </button>

                <!-- Link para Login -->
                <div id="fg-password" class="text-center mt-3">
                    <a href="{{ route('login') }}">
                        {{ __('Já tem conta? Entrar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
<script src="{{ asset('js/form-handler.js') }}"></script>
