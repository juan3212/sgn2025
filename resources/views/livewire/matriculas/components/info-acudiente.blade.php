<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Buscar o Registrar Acudiente</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-300 pb-6">
            <div class="max-sm:col-span-2">
                <label for="acudiente_tipo_doc" class="block text-sm font-medium text-gray-700">Tipo Documento</label>
                <select id="acudiente_tipo_doc" wire:model="acudienteTipoDocumento" required name="acudiente_tipo_doc"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="PPT">PPT</option>
                </select>
                <div>@error('acudienteTipoDocumento') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            </div>
            <div class="max-sm:col-span-2">
                <label for="acudiente_num_doc" class="block text-sm font-medium text-gray-700">Número Documento</label>
                <input type="text" wire:model="acudienteNumeroDocumento" required name="acudiente_num_doc" id="acudiente_num_doc"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('acudienteNumeroDocumento') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            </div>

            <div class="col-span-2">
                <button type="button" wire:click="searchAcudiente"
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
                    wire:model="acudienteNombre" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('acudienteNombre') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            </div>
            <div>
                <label for="acudiente_apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="acudiente_apellido" id="acudiente_apellido"
                    wire:model="acudienteApellido" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div>@error('acudienteApellido') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            </div>

            
            <livewire:matriculas.components.ubicacion wire:key="ubicacion_{{ $acudienteId }}" tipoUbicacion="expedicion" tipoUsuario="acudiente" :$departamento :$municipio />
                <div>@error('departamento') <span class="text-red-500">{{ $message }}</span> @enderror</div>
                <div>@error('municipio') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            
            <div class="md:col-span-2">
                <label for="parentesco" class="block text-sm font-medium text-gray-700">Parentesco</label>
                <select id="parentesco" name="parentesco"
                    wire:model="acudienteParentesco" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option selected="">Seleccione...</option>
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Hermano(a)">Hermano(a)</option>
                    <option value="Tio(a)">Tio(a)</option>
                    <option value="Abuelo(a)">Abuelo(a)</option>
                    <option value="Otro">Otro</option>
                </select>
                <div>@error('acudienteParentesco') <span class="text-red-500">{{ $message }}</span> @enderror</div>
            </div>
        </div>
        <livewire:matriculas.components.upload-document wire:key="documento_{{ $acudienteId }}" tipoDocumento="documento identidad (Acudiente) " usuario="acudiente" nombreDocumento="documento_identidad" :usuarioId="$acudienteId" :$estudianteId/>
    </fieldset>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            Livewire.on("acudiente", function (data) {

                Swal.fire({
                    icon: 'info',
                    title: data[0].title,
                    text: data[0].message,
                });
            });
        });
    </script>

</div>
