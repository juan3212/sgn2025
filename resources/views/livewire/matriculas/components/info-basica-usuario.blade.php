<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Información Básica</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" wire:model="nombre" id="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="apellido" wire:model="apellido" id="apellido" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="documento_tipo" class="block text-sm font-medium text-gray-700">Tipo Documento</label>
                <select id="documento_tipo" name="documento_tipo" wire:model="documentoTipo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="RC">Registro Civil</option>
                </select>
            </div>
            <div>
                <label for="numero_documento" class="block text-sm font-medium text-gray-700">Número Documento</label>
                <input type="text" name="numero_documento" wire:model="numeroDocumento" id="numero_documento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <livewire:matriculas.components.ubicacion wire:key="ubicacion_expedicion" tipoUbicacion="expedicion" tipoUsuario="estudiante" :departamento="$departamentoExpedicion" :municipio="$municipioExpedicion" />

            <div class="md:col-span-2">
                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha Nacimiento</label>
                <input type="date" name="fecha_nacimiento" wire:model="fechaNacimiento" id="fecha_nacimiento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <livewire:matriculas.components.ubicacion wire:key="ubicacion_nacimiento" tipoUbicacion="nacimiento"  tipoUsuario="estudiante" :departamento="$departamentoNacimiento" :municipio="$municipioNacimiento" />
            <div class="md:col-span-2">
                <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
                <select id="sexo" name="sexo" wire:model="sexo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option>Seleccione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>

        </div>
    </fieldset>

</div>
