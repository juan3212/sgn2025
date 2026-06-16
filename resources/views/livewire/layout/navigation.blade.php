<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-12 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @can('administracion general')
                    <x-nav-link :href="route('dashboard-administrador')" :active="request()->routeIs('dashboard-administrador')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endcan
                    @can('administrar usuarios', 'ver usuarios')
                    <x-nav-link :href="route('usuarios')" :active="request()->routeIs('usuarios')" wire:navigate>
                        {{ __('Usuarios') }}
                    </x-nav-link>
                    @endcan
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Materias') }}
                    </x-nav-link>
                    @can('administrar competencias')
                    <x-nav-link :href="route('competencias')" :active="request()->routeIs('competencias')" wire:navigate>
                        {{ __('Competencias') }}
                    </x-nav-link>
                    @endcan
                    @role('estudiante')
                   <!-- <x-nav-link :href="route('boletin')" :active="request()->routeIs('boletin')" wire:navigate>
                        {{ __('Boletines') }}
                    </x-nav-link> -->
                    @endrole
                    @can('administrar materias')
                    <x-nav-link :href="route('buscar-boletin')" :active="request()->routeIs('buscar-boletin')" wire:navigate>
                        {{ __('Boletines') }}
                    </x-nav-link>

                    <x-nav-link :href="route('informes')" :active="request()->routeIs('informes')" wire:navigate>
                        {{ __('Informes') }}
                    </x-nav-link>
                    @endcan
                    </div>

            </div>


            @can('administracion general')
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150">
                            <div>Ciclo: {{ session('school_year', '2026') }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('change-year', '2026')">
                            {{ __('2026 (Actual)') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('change-year', '2025')">
                            {{ __('2025 (Histórico)') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
            @endcan

            <!-- User Name and Settings Dropdown for Desktop -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 ">
                <div class="flex items-center mr-3"> <!-- User name -->
                    <p class="text-sm font-medium text-gray-500">
                        {{ Auth::user()->nombre . ' ' . Auth::user()->apellido }}
                    </p>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @can('administrar usuarios', 'ver usuarios')
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        @endcan

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <div class="pt-2 pb-3 space-y-1">
            @can('administracion general')
            <x-responsive-nav-link :href="route('dashboard-administrador')" :active="request()->routeIs('dashboard-administrador')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endcan
            @can('administrar usuarios')
            <x-responsive-nav-link :href="route('usuarios')" :active="request()->routeIs('usuarios')" wire:navigate>
                {{ __('Usuarios') }}
            </x-responsive-nav-link>
            @endcan
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Materias') }}
            </x-responsive-nav-link>
            @can('administrar competencias')
            <x-responsive-nav-link :href="route('competencias')" :active="request()->routeIs('competencias')" wire:navigate>
                {{ __('Competencias') }}
            </x-responsive-nav-link>
            @endcan
            @role('estudiante')
            <!--<x-responsive-nav-link :href="route('boletin')" :active="request()->routeIs('boletin')" wire:navigate>
                {{ __('Boletines') }}
            </x-responsive-nav-link> -->
            @endrole
            @can('administrar materias')
                <x-responsive-nav-link :href="route('buscar-boletin')" :active="request()->routeIs('buscar-boletin')" wire:navigate>
                    {{ __('Boletines') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('informes')" :active="request()->routeIs('informes')" wire:navigate>
                    {{ __('Informes') }}
                </x-responsive-nav-link>
            @endcan

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">

            <div class="mt-3 space-y-1">
                @can('administrar usuarios')
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                @endcan

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>

        </div>
    </div>
</nav>
