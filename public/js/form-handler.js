document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Form handler carregado');

    // Função principal para inicializar um formulário
    function initForm(form) {
        const formId = form.id || '(sem ID)';
        console.log(`📄 Inicializando formulário: ${formId}`);

        const isRegister = form.action.includes('register');
        const isLogin = form.action.includes('login');

        // Campos comuns
        const nameInput = form.querySelector('#name');
        const emailInput = form.querySelector('#email');
        const passwordInput = form.querySelector('#password');
        const confirmPasswordInput = form.querySelector('#password_confirmation');
        const togglePassword = form.querySelector('.password-toggle');
        const eyeIcon = form.querySelector('.eye-icon');
        const eyeOffIcon = form.querySelector('.eye-off-icon');
        const submitBtn = form.querySelector('button[type="submit"]');
        const btnText = form.querySelector('.btn-text');
        const loadingSpinner = form.querySelector('.loading-spinner');

        // Exibir erros do servidor
        form.querySelectorAll('.input-error').forEach((errorBox, i) => {
            if (errorBox.textContent.trim()) {
                console.log(`❌ Erro ${i + 1}:`, errorBox.textContent.trim());
                styleErrorBox(errorBox);
            }
        });

        // Toggle para senha
        if (togglePassword && eyeIcon && eyeOffIcon && passwordInput) {
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
            togglePassword.addEventListener('click', () => {
                const isPasswordType = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPasswordType ? 'text' : 'password');
                eyeIcon.style.display = isPasswordType ? 'none' : 'block';
                eyeOffIcon.style.display = isPasswordType ? 'block' : 'none';
            });
        }

        // Validação em tempo real
        if (emailInput) {
            const emailError = form.querySelector('#email-error');
            emailInput.addEventListener('blur', () => {
                if (emailInput.value && !validateEmail(emailInput.value)) {
                    showError(emailInput, emailError, 'Por favor, insira um email válido');
                } else {
                    clearError(emailInput, emailError);
                }
            });
            emailInput.addEventListener('input', () => clearError(emailInput, emailError));
        }
        if (passwordInput) {
            const passwordError = form.querySelector('#password-error');
            passwordInput.addEventListener('input', () => clearError(passwordInput, passwordError));
        }

        if (confirmPasswordInput) {
            const confirmError = form.querySelector('#password_confirmation-error');
            confirmPasswordInput.addEventListener('input', () => clearError(confirmPasswordInput, confirmError));
        }

        if (nameInput) {
            const nameError = form.querySelector('#name-error');
            nameInput.addEventListener('input', () => clearError(nameInput, nameError));
        }

        // Validação ao enviar o formulário
        form.addEventListener('submit', (e) => {
            console.log('📝 Validando antes de enviar');
            let hasErrors = false;

            // Validações do Login e Registro
            if (emailInput) {
                const emailError = form.querySelector('#email-error');
                if (!emailInput.value) {
                    showError(emailInput, emailError, 'O email é obrigatório');
                    hasErrors = true;
                } else if (!validateEmail(emailInput.value)) {
                    showError(emailInput, emailError, 'Por favor, insira um email válido');
                    hasErrors = true;
                }
            }

            if (passwordInput) {
                const passwordError = form.querySelector('#password-error');
                if (!passwordInput.value) {
                    showError(passwordInput, passwordError, 'A senha é obrigatória');
                    hasErrors = true;
                } else if (isRegister && passwordInput.value.length < 6) {
                    showError(passwordInput, passwordError, 'A senha deve ter pelo menos 6 caracteres');
                    hasErrors = true;
                }
            }

            // Campos extras para registro
            if (isRegister) {
                if (nameInput) {
                    const nameError = form.querySelector('#name-error');
                    if (!nameInput.value) {
                        showError(nameInput, nameError, 'O nome é obrigatório');
                        hasErrors = true;
                    }
                }
                if (confirmPasswordInput) {
                    const confirmError = form.querySelector('#password_confirmation-error');
                    if (!confirmPasswordInput.value) {
                        showError(confirmPasswordInput, confirmError, 'Confirme a sua senha');
                        hasErrors = true;
                    } else if (confirmPasswordInput.value !== passwordInput.value) {
                        showError(confirmPasswordInput, confirmError, 'As senhas não coincidem');
                        hasErrors = true;
                    }
                }
            }

            if (hasErrors) {
                console.log('❌ Erros encontrados. Formulário não enviado.');
                e.preventDefault();
                return;
            }

            // Loading
            if (submitBtn && btnText && loadingSpinner) {
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                loadingSpinner.style.display = 'flex';
            }
        });

        // Remove loading se houver erro do servidor
        if (form.querySelector('.input-error:not(:empty)') && submitBtn && btnText && loadingSpinner) {
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            loadingSpinner.style.display = 'none';
        }
    }

    // Funções auxiliares
    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    function showError(input, errorElement, message) {
        if (!errorElement) return;
        input.classList.add('error');
        errorElement.textContent = message;
        styleErrorBox(errorElement);
    }
    function clearError(input, errorElement) {
        if (!errorElement) return;
        input.classList.remove('error');
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
    function styleErrorBox(element) {
        element.style.display = 'block';
        element.style.backgroundColor = '#fef2f2';
        element.style.padding = '8px 12px';
        element.style.borderRadius = '6px';
        element.style.border = '1px solid #fecaca';
        element.style.marginTop = '8px';
    }

    // Inicializar todos os formulários que possuem .app-form
    document.querySelectorAll('.app-form').forEach(initForm);
});
