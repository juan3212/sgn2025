<div class="space-y-8">
    {{-- FORMULARIO DE REGISTRO --}}
    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Nuevo Acudiente</h3>
        
        {{-- Pasamos el control al componente blade --}}
        <x-matriculas.info-acudiente :uploadProperties="$form->getUploadProperties()" :estudianteId="$estudianteId"/>

        <hr class="my-6 border-gray-300">

        <x-matriculas.info-contacto tipoUsuario="acudiente" :form="$form"/>

        {{-- Lógica de visualización condicional usando .live --}}
        @if(in_array($form->parentesco, ['Padre', 'Madre']))
            <hr class="my-6 border-gray-300">
            <x-matriculas.info-financiera wire:key="info-financiera-{{ $form->acudienteId.$form->estado_laboral }}" :uploadProperties="$form->getUploadProperties()" :estudianteId="$estudianteId"/>
        @endif

        <div class="mt-6 flex justify-end">
            <button wire:click="guardar" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                Guardar y Agregar a la Lista
            </button>
        </div>
    </div>

    {{-- LISTA DE ACUDIENTES AGREGADOS --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Acudientes Agregados</h3>
        {{-- Aquí va tu tabla original iterando sobre $acudientes --}}
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parentesco</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($acudientes as $acudiente)
                    <tr>
                        <td class="px-6 py-4">{{ $acudiente->nombre }} {{ $acudiente->apellido }}</td>
                        <td class="px-6 py-4">{{ $acudiente->parentesco }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="quitarAcudiente({{ $acudiente->id }})" class="text-red-600 hover:text-red-900">Quitar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


