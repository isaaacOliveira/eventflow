<x-layouts::auth :title="__('Entrar')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Entra na tua Conta')" :description="__('Entrar com seu email e palavra passe')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Palavra passe')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('informe a palavra passe')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('ver palavra passe') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('lembrar-me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    Entrar
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Não é a tua conta?') }}</span>
                <flux:link :href="route('register')" wire:navigate>regista-se</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
