<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 shadow-sm">
                        <h3 class="text-lg font-bold text-blue-800 mb-2">Users & Roles</h3>
                        <p class="text-sm text-blue-600 mb-4">Manage your system users and their permissions.</p>
                        <div class="flex space-x-2">
                            <a href="{{ route('users') }}" class="text-blue-700 font-semibold hover:underline">Users →</a>
                            <a href="{{ route('roles') }}" class="text-blue-700 font-semibold hover:underline">Roles →</a>
                        </div>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-xl border border-purple-100 shadow-sm">
                        <h3 class="text-lg font-bold text-purple-800 mb-2">Location Data</h3>
                        <p class="text-sm text-purple-600 mb-4">Manage countries, states, and cities.</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('countries') }}" class="text-purple-700 font-semibold hover:underline">Countries →</a>
                            <a href="{{ route('states') }}" class="text-purple-700 font-semibold hover:underline">States →</a>
                            <a href="{{ route('cities') }}" class="text-purple-700 font-semibold hover:underline">Cities →</a>
                        </div>
                    </div>

                    <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100 shadow-sm">
                        <h3 class="text-lg font-bold text-emerald-800 mb-2">Catalog</h3>
                        <p class="text-sm text-emerald-600 mb-4">Manage brands and products catalog.</p>
                        <div class="flex space-x-2">
                            <a href="{{ route('brands') }}" class="text-emerald-700 font-semibold hover:underline">Brands →</a>
                            <a href="{{ route('products') }}" class="text-emerald-700 font-semibold hover:underline">Products →</a>
                        </div>
                    </div>

                    <div class="col-span-full mt-6">
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                             <h3 class="text-lg font-bold text-gray-800 mb-2">System Observability</h3>
                             <p class="text-sm text-gray-600 mb-4">Monitor system performance and detect anomalies.</p>
                             <a href="{{ url('/pulse') }}" class="inline-block bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">View Pulse Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
