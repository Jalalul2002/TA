<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 lg:px-0">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4 xl:gap-6">
            <!-- Card Total Pengajuan -->
            <div class="col-span-1">
                <div class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Pengajuan</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $totalPengajuan }}</p>
                    </div>
                    <div class="bg-uinBlue text-white p-3 rounded-full">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h11M9 21V3m5 18v-6" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Card Total Pengajuan -->
            <div class="col-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Pengajuan Diterima</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $totalPengajuan }}</p>
                    </div>
                    <div class="bg-uinTosca text-white p-3 rounded-full">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8V4m0 0L8 8m4-4l4 4M4 16h16" />
                        </svg>
                    </div>
                </div>
            </div>
</x-app-layout>
