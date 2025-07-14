<div>
    @can('administrar usuarios')
    <a href="{{ route('users.import.form') }}">
        <button class="w-full sm:w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Importar Usuarios
        </button>
    </a>
    @endcan
    <a href="{{ $createRoute }}">
        <button class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Agregar
        </button>
    </a>
    <button id="delete-selected" class="mt-2 sm:mt-0 w-full sm:w-auto bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed hidden">
        Eliminar seleccionados
    </button>
</div>