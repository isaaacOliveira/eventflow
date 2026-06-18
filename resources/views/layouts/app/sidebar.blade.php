<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Gestão de Eventos')" class="grid">
                    @canany(['visualizar_painel'])
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        Painel
                    </flux:sidebar.item>
                    @endcanany
                    @canany(['visualizar_eventos'])
                    <flux:sidebar.item icon="calendar" :href="route('eventos.index')" :current="request()->routeIs('eventos.index')" wire:navigate>
                        Eventos
                    </flux:sidebar.item>
                    @endcanany

                    @canany(['visualizar_inscricaos'])
                     <flux:sidebar.item icon="user-plus" :href="route('inscricaos.index')" :current="request()->routeIs('inscricaos.index')" wire:navigate>
                        Meus Ingressos
                    </flux:sidebar.item>
                    @endcanany

                    @canany(['visualizar_presencas'])
                     <flux:sidebar.item icon="identification" :href="route('presencas.index')" :current="request()->routeIs('eventos.index')" wire:navigate>
                        Presenças
                    </flux:sidebar.item>
                    @endcanany

                    @canany(['visualizar_users', 'visualizar_qualquer_users'])
                        
                    
                    <flux:sidebar.item icon="user-plus" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>
                        Usuarios
                    </flux:sidebar.item>

                    @endcanany
                    
                    @canany(['visualizar_roles'])
                    <flux:sidebar.item icon="lock-closed" :href="route('roles.index')" :current="request()->routeIs('roles.index')" wire:navigate>
                        Papeis
                    </flux:sidebar.item>
                    @endcanany

                    @canany(['visualizar_permissions'])
                    <flux:sidebar.item icon="key" :href="route('permissions.index')" :current="request()->routeIs('permissions.index')" wire:navigate>
                        Permissões
                    </flux:sidebar.item>
                    @endcanany  
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            Definições
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            Sair
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
