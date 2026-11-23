@props(['form', 'uploadProperties', 'estudianteId'])
<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Buscar o Registrar Acudiente</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-300 pb-6">
            <div class="max-sm:col-span-2">
                <label for="acudiente_tipo_doc" class="block text-sm font-medium text-gray-700">Tipo Documento <span class="text-red-500">*</span></label>
                <select id="acudiente_tipo_doc" wire:model="form.tipo_documento" required name="acudiente_tipo_doc"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="PPT">PPT</option>
                </select>
                <div>@error('form.tipo_documento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
            </div>
            <div class="max-sm:col-span-2">
                <label for="acudiente_num_doc" class="block text-sm font-medium text-gray-700">Número Documento <span class="text-red-500">*</span></label>
                <input type="text" wire:model.blur="form.numero_documento" required name="acudiente_num_doc" id="acudiente_num_doc"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('form.numero_documento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
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
                <label for="acudiente_nombre" class="block text-sm font-medium text-gray-700">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="acudiente_nombre" id="acudiente_nombre"
                    wire:model="form.nombre" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('form.nombre') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
            </div>
            <div>
                <label for="acudiente_apellido" class="block text-sm font-medium text-gray-700">Apellido <span class="text-red-500">*</span></label>
                <input type="text" name="acudiente_apellido" id="acudiente_apellido"
                    wire:model="form.apellido" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('form.apellido') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
            </div>

            <div class="md:col-span-2">
                <livewire:matriculas.components.ubicacion
                    wire:key="ubicacion_acudiente_{{ $form->acudienteId ?? 'nuevo' }}"
                    tipoUbicacion="expedicion"
                    tipoUsuario="acudiente"
                    wire:model="form.valueUbicacion"
                />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-1">
                    <div>@error('form.valueUbicacion.departamento') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
                    <div>@error('form.valueUbicacion.municipio') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="parentesco" class="block text-sm font-medium text-gray-700">Parentesco <span class="text-red-500">*</span></label>
                <select id="parentesco" name="parentesco"
                    wire:model.live="form.parentesco" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Padre/Madre_cabeza_de_familia">Padre/Madre cabeza de familia</option>
                    <option value="Hermano(a)">Hermano(a)</option>
                    <option value="Tio(a)">Tio(a)</option>
                    <option value="Abuelo(a)">Abuelo(a)</option>
                    <option value="Otro">Otro</option>
                </select>
                <div>@error('form.parentesco') <span class="text-sm text-red-500">{{ $message }}</span> @enderror</div>
            </div>
        </div>

        <fieldset class="border-2 {{ $errors->has('form.documento_identidad') ? 'border-red-400 bg-red-50/30' : ($form->documento_identidad_url ? 'border-green-300' : 'border-gray-300') }} p-6 rounded-lg mt-4">
            <legend class="text-xl font-semibold text-gray-700 px-2">Documento de identidad <span class="text-red-500">*</span></legend>
            <div>
                <label for="acudiente_documento_identidad" class="block text-sm font-medium text-gray-700">
                    Subir documento (PDF, JPG o PNG, máx. 10 MB)
                </label>
                <input type="file"
                    @if(!$form->documento_identidad_url) required @endif
                    id="acudiente_documento_identidad"
                    wire:model="form.documento_identidad"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <div wire:loading wire:target="form.documento_identidad" class="text-sm text-indigo-600 mt-1">Cargando archivo...</div>
                @error('form.documento_identidad') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="mt-2">
                @if($form->documento_identidad_url)
                    <span class="inline-flex items-center gap-2 text-sm text-green-700 font-medium">
                        Documento cargado:
                        <a href="{{ route('documentos.show', [$form->documento_identidad_url, time()]) }}" target="_blank" class="text-blue-600 hover:underline">Ver documento</a>
                    </span>
                @elseif($form->documento_identidad)
                    <span class="text-sm text-indigo-700 font-medium">Archivo listo para guardar: {{ $form->documento_identidad->getClientOriginalName() }}</span>
                @else
                    <span class="text-sm text-amber-700">Pendiente por subir.</span>
                @endif
            </div>
        </fieldset>

    </fieldset>
</div>
