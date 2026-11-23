<div class="space-y-8">
    {{-- FORMULARIO DE REGISTRO --}}
    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-4">
            {{ $form->acudienteId ? 'Editar Acudiente' : 'Nuevo Acudiente' }}
        </h3>

        <x-matriculas.info-acudiente :form="$form" :uploadProperties="$form->getUploadProperties()" :estudianteId="$estudianteId"/>

        <hr class="my-6 border-gray-300">

        <x-matriculas.info-contacto tipoUsuario="acudiente" :form="$form"/>

        {{-- Información financiera obligatoria para Padre, Madre o Padre/Madre cabeza de familia --}}
        @if(in_array($form->parentesco, ['Padre', 'Madre', 'Padre/Madre_cabeza_de_familia']))
            <hr class="my-6 border-gray-300">
            <x-matriculas.info-financiera :form="$form" :uploadProperties="$form->getUploadProperties()" :estudianteId="$estudianteId"/>
        @endif

        <div class="mt-6 flex justify-end">
            <button type="button" wire:click="guardar" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                Guardar y Agregar a la Lista
            </button>
        </div>
    </div>

    {{-- LISTA DE ACUDIENTES AGREGADOS --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Acudientes Agregados</h3>
        <p class="text-sm text-gray-600 mb-4">
            Para continuar debe registrar al <strong>Padre y a la Madre</strong>, o un <strong>Padre/Madre cabeza de familia</strong>, con todos sus datos obligatorios y documentos requeridos completos.
        </p>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parentesco</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($acudientes as $acudiente)
                        <tr>
                            <td class="px-6 py-4">{{ $acudiente['nombre'] }} {{ $acudiente['apellido'] }}</td>
                            <td class="px-6 py-4">{{ str_replace('_', ' ', $acudiente['parentesco']) }}</td>
                            <td class="px-6 py-4">
                                @if($acudiente['completo'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Incompleto
                                    </span>
                                    <ul class="mt-1 text-xs text-red-600 list-disc list-inside">
                                        @foreach($acudiente['errores'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-x-3 whitespace-nowrap">
                                <button type="button" wire:click="editarAcudiente('{{ $acudiente['nuip'] }}')" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    {{ $acudiente['completo'] ? 'Editar' : 'Completar' }}
                                </button>
                                <button type="button" wire:click="quitarAcudiente({{ $acudiente['id'] }})" class="text-red-600 hover:text-red-900 font-medium">
                                    Quitar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">
                                Aún no se han agregado acudientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
