@props(['uploadProperties', 'estudianteId'])
<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Buscar o Registrar Acudiente</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-300 pb-6">
            <div class="max-sm:col-span-2">
                <label for="acudiente_tipo_doc" class="block text-sm font-medium text-gray-700">Tipo Documento</label>
                <select id="acudiente_tipo_doc" wire:model="form.tipo_documento" required name="acudiente_tipo_doc"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="PPT">PPT</option>
                </select>
            </div>
            <div class="max-sm:col-span-2">
                <label for="acudiente_num_doc" class="block text-sm font-medium text-gray-700">Número Documento</label>
                <input type="text" wire:model.blur="form.numero_documento" required name="acudiente_num_doc" id="acudiente_num_doc"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('form.numero_documento') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            </div>

            <div class="col-span-2">
                <button type="button" wire:click="buscarAcudiente"
                    class="w-full px-3 h-10 border-2 border-green-500 rounded-lg shadow-sm flex items-center justify-center gap-2 text-green-700 bg-green-100 hover:bg-green-200">
                    <img src="{{ asset('img/icons/search.svg') }}" alt="Buscar" class="h-5 w-5">
                    <p class="text-sm font-medium text-green-700">Buscar Acudiente</p>
                </button>
            </div>
            <div id="mensaje_busqueda" class="text-red-500"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 mb-4">
            <div>
                <label for="acudiente_nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="acudiente_nombre" id="acudiente_nombre"
                    wire:model="form.nombre" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('form.nombre') <span class="text-red-500">{{ $message }}</span> @enderror</div>                       
            </div>
            <div>
                <label for="acudiente_apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="acudiente_apellido" id="acudiente_apellido"
                    wire:model="form.apellido" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('form.apellido') <span class="text-red-500">{{ $message }}</span> @enderror</div>                       
            </div>

            
            <livewire:matriculas.components.ubicacion wire:key="ubicacion_acudiente" tipoUbicacion="expedicion" tipoUsuario="acudiente" wire:model="form.valueUbicacion" />
            @error('form.valueUbicacion')
                <span class="text-red-500 mt-1">{{ $message }}</span>
            @enderror
            
            <div class="md:col-span-2">
                <label for="parentesco" class="block text-sm font-medium text-gray-700">Parentesco</label>
                <select id="parentesco" name="parentesco"
                    wire:model.live="form.parentesco" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option selected="" value="">Seleccione...</option>
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Padre/Madre_cabeza_de_familia">Padre/Madre cabeza de familia</option>
                    <option value="Hermano(a)">Hermano(a)</option>
                    <option value="Tio(a)">Tio(a)</option>
                    <option value="Abuelo(a)">Abuelo(a)</option>
                    <option value="Otro">Otro</option>
                </select>
                <div>@error('form.parentesco') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            </div>
        </div>

        <livewire:matriculas.components.upload-document wire:key="documento_{{ $uploadProperties['usuarioId']}}"
            tipoDocumento="documento de identidad" 
            usuario="acudiente" 
            nombreDocumento="documento_identidad" 
            :usuarioId="$uploadProperties['usuarioId']" 
            :$estudianteId 
            :usuario_nuip="$uploadProperties['usuarioNuip']"
        />

        
    </fieldset>
</div>