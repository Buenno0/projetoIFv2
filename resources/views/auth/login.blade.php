

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="min-height: 80vh;">
            <div class="card shadow-lg p-5 rounded-4 px-4" style="max-width: 500px; width: 100%;">

            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="{{ asset('assets/ifsp_logo_itp.png') }}" alt="Logo" style="max-width: 150px; height: 18vh;">
            </div>

            <h2 id="wlcm" class="text-center mb-4 font-weight-bold">{{ __('Bem-vindo!') }}</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" class="text-start d-block mb-2" />
                        <x-text-input 
                            id="email" 
                            class="form-control border-0 shadow-sm w-100 input-focused" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                            autocomplete="username" 
                            style="border-radius: 8px; height: 40px; padding: 10px;" 
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Senha')" class="text-start d-block mb-2" />
                        <x-text-input 
                            id="password" 
                            class="form-control border-0 shadow-sm w-100" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"  
                            style="border-radius: 8px; height: 40px; padding: 10px;" 
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-4">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="border-radius: 4px;">
                        <label class="form-check-label" for="remember_me">{{ __('Lembre-me') }}</label>
                    </div>

                    <!-- Login Button -->
                    <x-primary-button class="btn btn-primary py-2 mx-4" id="btn-entrar" style="border-radius: 8px; height: 55px; width: calc(100% - 32px);">
                        {{ __('Entrar') }}
                    </x-primary-button>
                    
                    <!-- Forgot Password Link (now below the button) -->
                    <div id="fg-password" class="text-center mt-3">
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none text-muted" href="{{ route('password.request') }}">
                                {{ __('Esqueceu sua senha?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
</x-guest-layout>
