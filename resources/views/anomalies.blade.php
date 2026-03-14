<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Anomaly Control Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Inject System Anomalies</h3>
                
                @if (session('message'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('message') }}
                    </div>
                @endif

                <form action="{{ route('anomalies.toggle') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Artificial Delay (ms)</label>
                        <input type="number" name="delay" value="{{ $status['delay_ms'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-700 text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Error Rate (%)</label>
                        <input type="number" name="error_rate" value="{{ $status['error_rate'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-700 text-white">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="inefficient" value="1" {{ $status['inefficient_db'] ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Inefficient DB Queries (N+1 Simulation)</label>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Apply Anomalies
                        </button>
                        
                        <button type="submit" name="reset" value="1" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Reset All
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
