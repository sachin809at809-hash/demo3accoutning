<x-layouts.admin>
    <x-slot name="title">
        {{ __('Brands') }}
    </x-slot>

    <div class="p-8 bg-white/60 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/50 mb-10 overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Brands') }}</h2>
            <button class="bg-gradient-to-r from-purple to-indigo-600 text-white px-6 py-2 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                + Add New
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-200 uppercase text-xs">
                        <th class="py-4 font-semibold">ID</th>
                        <th class="py-4 font-semibold">Details</th>
                        <th class="py-4 font-semibold">Status</th>
                        <th class="py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr class="border-b border-gray-100 hover:bg-white/40 transition-colors">
                        <td class="py-4">No records found.</td>
                        <td class="py-4"></td>
                        <td class="py-4"></td>
                        <td class="py-4 text-right"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>