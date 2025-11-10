<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Información Financiera (Acudiente)</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div>
                <label for="estado_laboral" class="block text-sm font-medium text-gray-700">Estado Laboral</label>
                <select id="estado_laboral" name="estado_laboral" wire:model.live="estadoLaboral" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="Empleado">Empleado</option>
                    <option value="Independiente">Independiente</option>
                    <option value="No trabaja">No trabaja</option>
                </select>
            </div>

            @if ($estadoLaboral == 'Empleado')
                <div>
                    <label for="empresa" class="block text-sm font-medium text-gray-700">Empresa que trabaja</label>
                    <input type="text" name="empresa" id="empresa" wire:model="empresa"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="certificado_laboral"
                        class="block text-sm font-medium text-gray-700">Certificado Laboral o Ingresos PDF</label>
                    <input type="file" name="certificado_laboral" id="certificado_laboral"
                        wire:model="certificadoLaboral"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            @elseif ($estadoLaboral == 'Independiente')
                <div>
                    <label for="certificado_laboral"
                        class="block text-sm font-medium text-gray-700">Certificado Laboral o Ingresos PDF</label>
                    <input type="file" name="certificado_laboral" id="certificado_laboral"
                        wire:model="certificadoLaboral"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <label for="camara_comercio" class="block text-sm font-medium text-gray-700">Cámara de Comercio
                        PDF</label>
                    <input type="file" name="camara_comercio" id="camara_comercio" wire:model="camaraComercio"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            @endif
        </div>
    </fieldset>
</div>
