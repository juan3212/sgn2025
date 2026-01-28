<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Periodos -->
                 @can('administrar periodos')
                <a href="{{ route('periodos') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Periodos</h3>
                            <p class="text-sm text-gray-500">Gestión de periodos académicos</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Informe Facturación -->
                 @can('administrar facturacion')
                <a href="{{ route('informes.facturacion-electronica.data') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Informe Facturación</h3>
                            <p class="text-sm text-gray-500">Reportes de facturación electrónica</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Gestión Roles -->
                @can('administrar roles')
                <a href="{{ route('gestion-roles') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Gestión Roles</h3>
                            <p class="text-sm text-gray-500">Administrar roles de usuarios</p>
                        </div>
                    </div>
                </a>
                @endcan
                
                <!-- Gestión Permisos -->
                @can('administrar permisos')
                <a href="{{ route('gestion-permisos') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Gestión Permisos</h3>
                            <p class="text-sm text-gray-500">Administrar permisos del sistema</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Gestión Pagos -->
                @can('administrar pagos')
                <a href="{{ route('gestion-pagos') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Gestión Pagos</h3>
                            <p class="text-sm text-gray-500">Control de pagos y matrículas</p>
                        </div>
                    </div>
                </a>
                @endcan
            </div>

            @role('Super-Admin')
            <div class="bg-white p-6 rounded-lg shadow-lg mt-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800">🚀 Transición de Año Escolar</h3>
                <p class="text-gray-600 mb-4">
                    Esta acción clonará la base de datos actual, limpiará las notas y promoverá a los estudiantes al siguiente grado automáticamente.
                </p>

                <form action="{{ route('crear.ciclo') }}" method="POST" onsubmit="return confirm('¿ESTÁS SEGURO? \n\nEsto creará una nueva base de datos y moverá a todos los estudiantes de grado. Esta acción es irreversible.');">
                    @csrf
                    
                    <div class="flex items-center gap-4">
                        <div>
                            <label for="anio" class="block text-sm font-medium text-gray-700">Nuevo Año:</label>
                            <input type="number" name="anio_destino" value="{{ date('Y') + 1 }}" class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 mt-5">
                            Iniciar Proceso Automático
                        </button>
                    </div>
                </form>

                @if (session('console_output'))
                    <div class="mt-4 p-4 bg-gray-900 text-green-400 font-mono text-sm rounded h-48 overflow-y-auto">
                        {!! session('console_output') !!}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            @endrole
        </div>
    </div>
</x-app-layout>
