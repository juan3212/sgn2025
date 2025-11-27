
@props(['uploadProperties','estudianteId'])
    
<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Información Financiera (Acudiente)</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div @if($uploadProperties['estadoLaboral'] == 'Independiente') class="col-span-2" @endif>
                <label for="estado_laboral" class="block text-sm font-medium text-gray-700">Estado Laboral</label>
                <select id="estado_laboral" name="estado_laboral" wire:model.live="form.estado_laboral" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="Empleado">Empleado</option>
                    <option value="Independiente">Independiente</option>
                    <option value="No trabaja">No trabaja</option>
                </select>
            </div>

            @if ($uploadProperties['estadoLaboral'] == 'Empleado')

             
                <div>
                    <label for="empresa" class="block text-sm font-medium text-gray-700">Empresa que trabaja</label>
                    <input type="text" name="empresa" id="empresa" wire:model="form.empresa"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <livewire:matriculas.components.upload-document wire:key="certificado_laboral_{{ $uploadProperties['usuarioId'] }}" 
                    tipoDocumento="certificado laboral" 
                    usuario="acudiente" 
                    nombreDocumento="certificado_laboral" 
                    :usuarioId="$uploadProperties['usuarioId']" 
                    :$estudianteId 
                    :usuario_nuip="$uploadProperties['usuarioNuip']"
                />
            @elseif ($uploadProperties['estadoLaboral'] == 'Independiente')
                <livewire:matriculas.components.upload-document wire:key="camara_comercio_{{ $uploadProperties['usuarioId'] }}" 
                    tipoDocumento="camara de comercio" 
                    usuario="acudiente" 
                    nombreDocumento="camara_comercio" 
                    :usuarioId="$uploadProperties['usuarioId']" 
                    :$estudianteId 
                    :usuario_nuip="$uploadProperties['usuarioNuip']"
                />

                 <livewire:matriculas.components.upload-document wire:key="certificado_ingresos_{{ $uploadProperties['usuarioId'] }}" 
                    tipoDocumento="certificado de ingresos" 
                    usuario="acudiente" 
                    nombreDocumento="certificado_ingresos" 
                    :usuarioId="$uploadProperties['usuarioId']" 
                    :$estudianteId 
                    :usuario_nuip="$uploadProperties['usuarioNuip']"
                />
            @endif
        </div>
    </fieldset>
</div>