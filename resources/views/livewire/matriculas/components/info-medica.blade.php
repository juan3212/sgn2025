<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Información Médica</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div>
                <label for="rh" class="block text-sm font-medium text-gray-700">RH <span class="text-red-500">*</span></label>
                <input type="text" name="rh" wire:model="rh" id="rh" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('rh') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="eps" class="block text-sm font-medium text-gray-700">EPS <span class="text-red-500">*</span></label>
                <input type="text" name="eps" wire:model="eps" id="eps" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('eps') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label for="enfermedades" class="block text-sm font-medium text-gray-700">Enfermedades (opcional)</label>
                <textarea name="enfermedades" wire:model="enfermedades" id="enfermedades" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="md:col-span-2">
                <label for="alergias" class="block text-sm font-medium text-gray-700">Alergias (opcional)</label>
                <textarea name="alergias" wire:model="alergias" id="alergias" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="md:col-span-2">
                <label for="medicamentos" class="block text-sm font-medium text-gray-700">Medicamentos (opcional)</label>
                <textarea name="medicamentos" wire:model="medicamentos" id="medicamentos" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
        </div>
    </fieldset>

</div>
