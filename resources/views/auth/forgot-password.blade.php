<x-guest-layout>
    <div class="text-center mb-4">
        <img src="{{ asset('assets/ifsp_logo_itp.png') }}" alt="Logo" style="max-width: 150px; height: 18vh;">
    </div>
    <h1 class="text-center">Redefinicão de senha</h1>
    
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e enviaremos um link de redefinição de senha que permitirá que você escolha uma nova.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4 mb-4">
            <x-primary-button class="btn btn-primary w-100 py-2" id="btn-entrar" style="border-radius: 8px; height: 55px;">
            {{ __('Redefinir senha') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
