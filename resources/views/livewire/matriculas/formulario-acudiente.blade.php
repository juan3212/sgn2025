<div>
    <livewire:matriculas.components.info-acudiente wire:key="info_acudiente" :$estudianteId />
    <livewire:matriculas.components.info-contacto  tipoUsuario="acudiente" wire:key="contacto_{{ $acudienteId }}" :$estudianteId :usuarioId="$acudienteId" />
    <livewire:matriculas.components.info-financiera wire:key="financiera_{{ $acudienteId }}" :$estudianteId :usuarioId="$acudienteId" />
    <div class="flex justify-center py-4">
        <button type="button" class="w-full p-3 px-6 border-2 border-blue-600 text-blue-700 rounded-md shadow-sm hover:bg-blue-600 hover:text-white transition"
        wire:click="agregarAcudiente"
        >
        Agregar Acudiente
        </button>
    </div>

    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Acudientes Agregados</h3>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Apellido</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Documento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Parentesco</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Opciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($acudientes as $acudiente)
                        <tr wire:key="acudiente_{{ $acudiente->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $acudiente->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $acudiente->apellido }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $acudiente->nuip }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $acudiente->telefono }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $acudiente->parentesco }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="text-red-600 hover:text-red-900"
                                
                                >
                                Quitar
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">No hay acudientes agregados.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
