<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
  
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
      <form>
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800 grid grid-cols-2 gap-4">
        <div class="mb-4 col-span-2">
            <label for="name" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Name:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" placeholder="Enter Name" wire:model.defer="name">
            @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Email:</label>
            <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" placeholder="Enter Email" wire:model.defer="email">
            @error('email') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Password (Leave blank to keep):</label>
            <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" placeholder="Enter Password" wire:model.defer="password">
            @error('password') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        
        <div class="mb-4">
            <label for="role_id" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Role:</label>
            <select wire:model.defer="role_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="role_id">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="dob" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Date of Birth:</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="dob" wire:model.defer="dob">
            @error('dob') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="mobile_number" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Mobile Number:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="mobile_number" placeholder="Enter Mobile Number" wire:model.defer="mobile_number">
            @error('mobile_number') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        
        <div class="mb-4">
            <label for="country_id" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Country:</label>
            <select wire:model.defer="country_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="country_id">
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
            @error('country_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="state_id" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">State:</label>
            <select wire:model.defer="state_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="state_id">
                <option value="">Select State</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
            </select>
            @error('state_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="city_id" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">City:</label>
            <select wire:model.defer="city_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="city_id">
                <option value="">Select City</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
            @error('city_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </div>

        <div class="mb-4">
            <label for="gender" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Gender:</label>
            <select wire:model.defer="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="gender">
               <option value="">Select Gender</option>
               <option value="M">Male</option>
               <option value="F">Female</option>
            </select>
            @error('gender') <span class="text-red-500">{{ $message }}</span>@enderror
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
