<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prediksi BHP') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full max-w-md p-8 space-y-4 rounded-lg">
                <<h2 class="text-2xl font-bold text-gray-800 text-center">Upload CSV File</h2>
                <form action="/send-data" method="POST" enctype="multipart/form-data" x-data="{ fileName: '' }">
                    @csrf
                    <label class="block mb-2 text-gray-700">Select CSV File</label>
                    <input type="file" name="csv_file" class="hidden" x-ref="csv" @change="fileName = $refs.csv.files[0].name">
                    <div class="flex items-center justify-between border rounded p-2 cursor-pointer bg-gray-50" @click="$refs.csv.click()">
                        <span x-text="fileName || 'Choose File'"></span>
                        <button type="button" class="px-2 py-1 text-sm text-white bg-blue-600 rounded">Browse</button>
                    </div>
                    <button type="submit" class="w-full mt-4 px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Upload</button>
                    @error('csv_file')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
