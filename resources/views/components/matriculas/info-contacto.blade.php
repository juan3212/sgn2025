@props(['tipoUsuario', 'form'])
<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Información de Contacto {{ ucfirst($tipoUsuario) }}</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div class="max-sm:col-span-2">
                <label for="telefono" class="text-sm font-medium text-gray-700" @if($tipoUsuario == "acudiente" && !$form->contacto) required @endif>Teléfono @if($tipoUsuario == "estudiante") (opcional) @endif</label>
                <input type="tel" name="telefono" id="telefono" wire:model="form.telefono" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div class="text-sm text-red-500">@error('form.telefono') {{ $message }} @enderror</div>
            </div>
            <div class="max-sm:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700" @if($tipoUsuario == "acudiente" && !$form->contacto) required @endif>Correo @if($tipoUsuario == "estudiante") (opcional) @endif</label>
                <input type="email" name="email" id="email" wire:model="form.email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div class="text-sm text-red-500">@error('form.email') {{ $message }} @enderror</div>
            </div>
            <div class="col-span-2">
                <label for="direccion" class="block text-sm font-medium text-gray-700" @if($tipoUsuario == "acudiente" && !$form->contacto) required @endif>Dirección de Residencia</label>
                <input type="text" name="direccion" id="direccion" wire:model="form.direccion" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div class="text-sm text-red-500">@error('form.direccion') {{ $message }} @enderror</div>
            </div>

            @if($tipoUsuario == "acudiente")
            <div class="md:col-span-2 mt-6">
                <button 
                    type="button" 
                    wire:click="guardarContacto"
                    class="inline-flex justify-center w-full py-2 px-4 h-10 border border-stone-300 shadow-sm text-sm font-medium rounded-md text-blue-700 underline bg-stone-50 hover:bg-stone-200">
                    Agregar Contacto
                </button>
            </div>
            @endif          
        </div>
        
        @if($tipoUsuario == "acudiente")  
            <div 
            class="grid grid-cols-1 gap-6 mt-4 overflow-x-auto"
            >
                <table class="mt-6 rounded-lg shadow-sm border border-gray-200" id="contacto-table">
                    <thead>
                        <tr class="bg-gray-100 font-semibold rounded-tl-lg rounded-tr-lg">
                            <th class="px-4 py-2">Teléfono</th>
                            <th class="px-4 py-2">Correo</th>
                            <th class="px-4 py-2">Dirección</th>
                            <th class="px-4 py-2">Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($form->contacto)
                            @foreach($form->contacto as $item)
                                <tr class="text-sm text-gray-500">
                                    <td class="px-4 py-2">{{ $item->telefono }}</td>
                                    <td class="px-4 py-2">{{ $item->email }}</td>
                                    <td class="px-4 py-2">{{ $item->direccion }}</td>
                                    <td class="px-4 py-2">
                                        <button 
                                            type="button" 
                                            class="inline-flex justify-center w-full py-2 px-4 h-10 border border-stone-300 shadow-sm text-sm font-medium rounded-md text-blue-700 underline bg-stone-50 hover:bg-stone-200"
                                            wire:click="quitarContacto({{ $item->id }})"
                                            >
                                            Borrar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-sm text-gray-500" colspan="4"></tr>
                        <tr class="text-sm text-gray-500" colspan="4">
                            <td colspan="4" class="px-4 py-2">No hay contactos agregados.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </fieldset>
</div>