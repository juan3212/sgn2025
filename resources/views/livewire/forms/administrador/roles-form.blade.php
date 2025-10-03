<div class="flex flex-col items-center py-10 min-h-screen bg-gray-100">

    <div class="flex justify-start align-end items-end w-full mt-4 max-w-5xl">
        <a href="{{ route('gestion-roles') }}" class="flex justify-start items-end mb-4 underline text-black">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><!-- Icon from Material Design Icons by Pictogrammers - https://github.com/Templarian/MaterialDesign/blob/master/LICENSE --><path fill="currentColor" d="M20 13.5a6.5 6.5 0 0 1-6.5 6.5H6v-2h7.5c2.5 0 4.5-2 4.5-4.5S16 9 13.5 9H7.83l3.08 3.09L9.5 13.5L4 8l5.5-5.5l1.42 1.41L7.83 7h5.67a6.5 6.5 0 0 1 6.5 6.5"/></svg> 
            <span class="ml-2">Volver</span>
        </a>
    </div>

    <div class="w-full max-w-5xl p-8 space-y-6 bg-white rounded shadow-md md:w-full md:p-4">

        @if (session()->has('message'))
            <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
                {{ session('message') }}
            </div>
        @endif
        <form wire:submit.prevent="save" class="space-y-6">
            <div class="flex flex-col space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" wire:model="name" id="name" name="name"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500">
            </div>
            <div class="flex flex-col space-y-2">
                <label for="permissions" class="block text-sm font-medium text-gray-700">Permisos</label>
                <select wire:model="permissionSelected" id="permissions" name="permissions" multiple
                    class="mt-1 block w-full h-min-xl px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500">
                    @foreach ($permissions as $permission)
                    @if ($permissionSelected && array_search($permission->name, $permissionSelected))
                        <option value="{{ $permission->name }}" selected>{{ $permission->name }}</option>
                    @else
                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Guardar</button>
        </form>
    </div>
</div>
