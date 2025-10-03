<div>
    @if ($show)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40" wire:click="close"></div>

    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-auto">
            
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-semibold">Título del Modal</h3>
                <button wire:click="close" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>

            <div class="mt-4">
                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="flex flex-col">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" wire:model="name" id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col">
                        <label for="guard_name" class="block text-sm font-medium text-gray-700">Guard Name</label>
                        <select wire:model="guard_name" id="guard_name" name="guard_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500">
                            <option value="web">web</option>
                            <option value="api">api</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="mt-6 flex justify-end">
                <button wire:click="close" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Cancelar
                </button>
                <button type="submit"  wire:click="save" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Guardar
                </button>
            </div>

        </div>
    </div>
    @endif
</div>