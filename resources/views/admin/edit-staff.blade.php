<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('admin.staff') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('◀️ Edit Data User') }}
            </h2>
        </a>
    </x-slot>
    <div class="py-6" x-data="{ usertype: '{{ $user->usertype }}', prodiRequired: '{{ $user->usertype }}' === 'staff' }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-2 xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.update-staff', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="usertype" :value="__('Usertype')" />
                        <select id="usertype" name="usertype" x-model="usertype"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            @change="prodiRequired = (usertype === 'staff')">
                            <option value="">-- Pilih Tipe Pengguna --</option>
                            <option value="admin" {{ $user->usertype == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ $user->usertype == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="user" {{ $user->usertype == 'user' ? 'selected' : '' }}>User</option>

                        </select>
                        <x-input-error :messages="$errors->get('usertype')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="prodi" :value="__('Program Studi (staff wajib diisi)')" />
                        <select id="prodi" name="prodi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            :required="prodiRequired">
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="Matematika" {{ $user->prodi == 'Matematika' ? 'selected' : '' }}>
                                701-Matematika</option>
                            <option value="Biologi" {{ $user->prodi == 'Biologi' ? 'selected' : '' }}>702-Biologi
                            </option>
                            <option value="Fisika" {{ $user->prodi == 'Fisika' ? 'selected' : '' }}>703-Fisika</option>
                            <option value="Kimia" {{ $user->prodi == 'Kimia' ? 'selected' : '' }}>704-Kimia</option>
                            <option value="Teknik Informatika"
                                {{ $user->prodi == 'Teknik Informatika' ? 'selected' : '' }}>705-Teknik Informatika
                            </option>
                            <option value="Agroteknologi" {{ $user->prodi == 'Agroteknologi' ? 'selected' : '' }}>
                                706-Agroteknologi</option>
                            <option value="Teknik Elektro" {{ $user->prodi == 'Teknik Elektro' ? 'selected' : '' }}>
                                707-Teknik Elektro</option>
                        </select>
                        <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password (Kosongkan Jika Tidak Ingin Diubah!)')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                            autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.staff') }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                        <x-primary-button class="ms-4">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>
