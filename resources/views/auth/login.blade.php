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
            
            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
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
                required 
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

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Elementos
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.querySelector('.eye-icon');
    const eyeOffIcon = document.querySelector('.eye-off-icon');
    const submitBtn = document.getElementById('btn-entrar');
    const btnText = document.querySelector('.btn-text');
    const loadingSpinner = document.querySelector('.loading-spinner');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    });

    // Email validation
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Show error message
    function showError(inputElement, errorElement, message) {
        inputElement.classList.add('error');
        errorElement.textContent = message;
        errorElement.style.display = 'flex';
        
        // Adiciona feedback visual ao botão
        submitBtn.classList.add('error-state');
        setTimeout(() => {
            submitBtn.classList.remove('error-state');
        }, 500);
    }

    // Clear error message
    function clearError(inputElement, errorElement) {
        inputElement.classList.remove('error');
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }

    // Clear all errors
    function clearAllErrors() {
        const errorElements = document.querySelectorAll('.input-error');
        const inputElements = document.querySelectorAll('.form-control');
        
        errorElements.forEach(error => {
            if (!error.textContent.trim()) {
                error.style.display = 'none';
            }
        });
        
        inputElements.forEach(input => {
            if (!input.classList.contains('error')) {
                input.classList.remove('error');
            }
        });
    }

    // Real-time email validation
    emailInput.addEventListener('blur', function() {
        const emailError = document.getElementById('email-error');
        const email = this.value.trim();
        
        if (email && !validateEmail(email)) {
            showError(this, emailError, 'Por favor, insira um email válido');
        } else if (!emailError.textContent.includes('credenciais')) {
            clearError(this, emailError);
        }
    });

    // Clear errors on input
    emailInput.addEventListener('input', function() {
        const emailError = document.getElementById('email-error');
        if (this.classList.contains('error') && !emailError.textContent.includes('credenciais')) {
            clearError(this, emailError);
        }
    });

    passwordInput.addEventListener('input', function() {
        const passwordError = document.getElementById('password-error');
        if (this.classList.contains('error') && !passwordError.textContent.includes('obrigatório')) {
            clearError(this, passwordError);
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        let hasErrors = false;

        // Clear previous client-side errors
        clearAllErrors();

        // Validate email
        const email = emailInput.value.trim();
        if (!email) {
            showError(emailInput, emailError, 'O email é obrigatório');
            hasErrors = true;
        } else if (!validateEmail(email)) {
            showError(emailInput, emailError, 'Por favor, insira um email válido');
            hasErrors = true;
        }

        // Validate password
        const password = passwordInput.value;
        if (!password) {
            showError(passwordInput, passwordError, 'A senha é obrigatória');
            hasErrors = true;
        }

        if (hasErrors) {
            e.preventDefault();
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        loadingSpinner.style.display = 'flex';
    });

    // Auto-focus no campo com erro (caso exista erro do servidor)
    const fieldWithError = document.querySelector('.form-control.error');
    if (fieldWithError) {
        fieldWithError.focus();
        
        // Se o erro for de credenciais, limpa após 5 segundos
        const errorElement = fieldWithError.parentElement.querySelector('.input-error');
        if (errorElement && errorElement.textContent.includes('credenciais')) {
            setTimeout(() => {
                clearError(fieldWithError, errorElement);
            }, 8000);
        }
    }

    // Remove loading state se houver erros do servidor
    if (document.querySelector('.input-error:not(:empty)')) {
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        loadingSpinner.style.display = 'none';
    }
});

</x-guest-layout>
