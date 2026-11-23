<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Información Básica</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" wire:model="nombre" id="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('nombre') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido <span class="text-red-500">*</span></label>
                <input type="text" name="apellido" wire:model="apellido" id="apellido" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('apellido') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="documento_tipo" class="block text-sm font-medium text-gray-700">Tipo Documento <span class="text-red-500">*</span></label>
                <select id="documento_tipo" name="documento_tipo" wire:model="documentoTipo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="RC">Registro Civil</option>
                </select>
                @error('documentoTipo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="numero_documento" class="block text-sm font-medium text-gray-700">Número Documento <span class="text-red-500">*</span></label>
                <input type="text" name="numero_documento" wire:model="numeroDocumento" id="numero_documento" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('numeroDocumento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <livewire:matriculas.components.ubicacion wire:key="ubicacion_expedicion" tipoUbicacion="expedicion" tipoUsuario="estudiante" wire:model="valueUbicacionExpedicion" :departamento="$departamentoExpedicion" :municipio="$municipioExpedicion" />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-1">
                    <div>@error('valueUbicacionExpedicion.departamento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
                    <div>@error('valueUbicacionExpedicion.municipio') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha Nacimiento <span class="text-red-500">*</span></label>
                <input type="date" name="fecha_nacimiento" wire:model="fechaNacimiento" id="fecha_nacimiento" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('fechaNacimiento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <livewire:matriculas.components.ubicacion wire:key="ubicacion_nacimiento" tipoUbicacion="nacimiento" tipoUsuario="estudiante" wire:model="valueUbicacionNacimiento" :departamento="$departamentoNacimiento" :municipio="$municipioNacimiento" />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-1">
                    <div>@error('valueUbicacionNacimiento.departamento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
                    <div>@error('valueUbicacionNacimiento.municipio') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo <span class="text-red-500">*</span></label>
                <select id="sexo" name="sexo" wire:model="sexo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
                @error('sexo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

        </div>
    </fieldset>

</div>
