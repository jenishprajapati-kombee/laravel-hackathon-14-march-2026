<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
  
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
      <form>
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
        <div class="mb-4">
            <label for="brand_id" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Brand:</label>
            <select wire:model.defer="brand_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="brand_id">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
            @error('brand_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="name" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Name:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" placeholder="Enter Name" wire:model.defer="name">
            @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="price" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Price:</label>
            <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" placeholder="Enter Price" wire:model.defer="price">
            @error('price') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="stock" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Stock:</label>
            <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="stock" placeholder="Enter Stock" wire:model.defer="stock">
            @error('stock') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="sku" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">SKU:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="sku" placeholder="Enter SKU" wire:model.defer="sku">
            @error('sku') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Description:</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" placeholder="Enter Description" wire:model.defer="description"></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="status" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Status:</label>
            <select wire:model.defer="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status">
               <option value="Y">Active</option>
               <option value="N">Inactive</option>
            </select>
            @error('status') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
          <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
            Save
          </button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
          <button wire:click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
            Cancel
          </button>
        </span>
      </div>
      </form>
    </div>
  </div>
</div>
