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
    console.log('🚀 Login script carregado');
    
    // Verificar se há erros do servidor
    const serverErrors = document.querySelectorAll('.input-error');
    serverErrors.forEach((error, index) => {
        if (error.textContent.trim()) {
            console.log(`❌ Erro ${index + 1}:`, error.textContent.trim());
            error.style.display = 'block';
            error.style.backgroundColor = '#fef2f2';
            error.style.padding = '8px 12px';
            error.style.borderRadius = '6px';
            error.style.border = '1px solid #fecaca';
        }
    });

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

    // Toggle password visibility - CÓDIGO ÚNICO E CORRIGIDO
    if (togglePassword && eyeIcon && eyeOffIcon) {
        console.log('👁️ Inicializando toggle de senha');
        
        // Estado inicial: senha oculta
        eyeIcon.style.display = 'block';
        eyeOffIcon.style.display = 'none';
        
        togglePassword.addEventListener('click', function() {
            console.log('👁️ Toggle clicado');
            const currentType = passwordInput.getAttribute('type');
            const newType = currentType === 'password' ? 'text' : 'password';
            
            passwordInput.setAttribute('type', newType);
            
            if (newType === 'text') {
                // Senha visível - mostrar olho cortado
                console.log('👀 Mostrando senha');
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                // Senha oculta - mostrar olho normal
                console.log('🙈 Ocultando senha');
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        });
    } else {
        console.log('❌ Elementos do toggle não encontrados');
    }

    // Funções de validação
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showError(inputElement, errorElement, message) {
        console.log('🔴 Mostrando erro:', message);
        inputElement.classList.add('error');
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        errorElement.style.backgroundColor = '#fef2f2';
        errorElement.style.padding = '8px 12px';
        errorElement.style.borderRadius = '6px';
        errorElement.style.border = '1px solid #fecaca';
        errorElement.style.marginTop = '8px';
    }

    function clearError(inputElement, errorElement) {
        inputElement.classList.remove('error');
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }

    // Validação em tempo real
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailError = document.getElementById('email-error');
            const email = this.value.trim();
            
            if (email && !validateEmail(email)) {
                showError(this, emailError, 'Por favor, insira um email válido');
            } else if (!emailError.textContent.includes('incorretos')) {
                clearError(this, emailError);
            }
        });

        emailInput.addEventListener('input', function() {
            const emailError = document.getElementById('email-error');
            if (this.classList.contains('error') && !emailError.textContent.includes('incorretos')) {
                clearError(this, emailError);
            }
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const passwordError = document.getElementById('password-error');
            if (this.classList.contains('error') && !passwordError.textContent.includes('obrigatório')) {
                clearError(this, passwordError);
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('📝 Formulário enviado');
            
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            let hasErrors = false;

            // Validar email
            const email = emailInput.value.trim();
            if (!email) {
                showError(emailInput, emailError, 'O email é obrigatório');
                hasErrors = true;
            } else if (!validateEmail(email)) {
                showError(emailInput, emailError, 'Por favor, insira um email válido');
                hasErrors = true;
            }

            // Validar senha
            const password = passwordInput.value;
            if (!password) {
                showError(passwordInput, passwordError, 'A senha é obrigatória');
                hasErrors = true;
            }

            if (hasErrors) {
                console.log('❌ Formulário com erros, prevenindo envio');
                e.preventDefault();
                return;
            }

            // Loading state
            if (submitBtn && btnText && loadingSpinner) {
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                loadingSpinner.style.display = 'flex';
            }
        });
    }

    // Remover loading se houver erros do servidor
    if (document.querySelector('.input-error:not(:empty)')) {
        console.log('🔄 Removendo loading devido a erros do servidor');
        if (submitBtn && btnText && loadingSpinner) {
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            loadingSpinner.style.display = 'none';
        }
    }
});
</script>


</x-guest-layout>
