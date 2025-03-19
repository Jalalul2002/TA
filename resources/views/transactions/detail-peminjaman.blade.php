<x-app-layout>
    @php
        $kodeBarang = [
            '' => '700',
            'Matematika' => '701',
            'Biologi' => '702',
            'Fisika' => '703',
            'Kimia' => '704',
            'Teknik Informatika' => '705',
            'Agroteknologi' => '706',
            'Teknik Elektro' => '707',
        ];
        $defaultKode = $kodeBarang[$dataTransaksi->location] ?? '700';
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('peminjaman') }}">
                {{ __('◀️ Detail Peminjaman Barang') }} {{ $dataTransaksi->name }}
            </a>
        </h2>
    </x-slot>
    @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed top-20 right-10 p-3 rounded-md shadow-md transition-all duration-500"
            :class="{ 'bg-green-500 text-white': '{{ session('success') }}', 'bg-red-500 text-white': '{{ session('error') }}' }"
            x-transition:enter="transform ease-out duration-500" x-transition:enter-start="opacity-0 -translate-y-5"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transform ease-in duration-500"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-5">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
    <div class="py-4" x-data="{
        openEdit: false,
        selectedProduct: null,
        selectedProductCode: '',
        selectedProductName: '',
        selectedProductDetail: '',
        selectedProductMerk: '',
        selectedProductQuantity: 0,
        selectedReturnedQuantity: 0,
        selectedDamagedQuantity: 0,
        openModal(productId, productCode, productName, productQuantity, returnedQuantity, damagedQuantity, productDetail, productMerk) {
            this.selectedProduct = productId;
            this.selectedProductCode = productCode;
            this.selectedProductName = productName;
            this.selectedProductDetail = productDetail;
            this.selectedProductMerk = productMerk;
            this.selectedProductQuantity = productQuantity;
            this.selectedReturnedQuantity = returnedQuantity;
            this.selectedDamagedQuantity = damagedQuantity;
            this.openEdit = true;
        }
    }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center space-x-1 xl:space-x-2 justify-end mb-4">
                <a href="{{ route('peminjaman.export.id', $dataTransaksi->id) }}">
                    <div
                        class="inline-flex gap-x-2 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 duration-300 transition-all">
                        <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                            data-name="Layer 1" viewBox="0 0 24 24">
                            <path
                                d="M23.971,17.98c-.191,1.175-1.215,2.028-2.436,2.028-.664,0-1.301-.196-1.84-.553-.387-.257-.447-.801-.118-1.129l.002-.002c.236-.235,.602-.283,.886-.11,.316,.191,.689,.298,1.07,.298,.604,0,1.031-.404,1.096-.795,.088-.55-.602-.873-.817-.959-.915-.371-1.751-.78-1.753-.78-.692-.484-1.027-1.231-.921-2.049,.111-.845,.674-1.534,1.471-1.796,.839-.277,1.603-.076,2.152,.203,.405,.205,.518,.733,.24,1.093-.203,.263-.559,.358-.863,.225-.306-.134-.71-.226-1.137-.088-.412,.136-.517,.447-.515,.577,.005,.442,.335,.57,.433,.615,.282,.128,.806,.368,1.366,.595,1.461,.591,1.828,1.745,1.685,2.627Zm-8.368-5.972h0c-.442,0-.8,.358-.8,.8v6.401c0,.442,.358,.8,.8,.8h2.133c.442,0,.8-.358,.8-.8s-.358-.8-.8-.8c-.755,0-1.333,0-1.333,0v-5.601c0-.442-.358-.8-.8-.8Zm-2.612,0c-.307,0-.589,.174-.726,.449l-.864,1.733-.864-1.733c-.137-.275-.418-.449-.726-.449-.602,0-.994,.634-.726,1.173l1.409,2.827-1.409,2.827c-.269,.539,.123,1.173,.726,1.173h0c.307,0,.589-.174,.726-.449l.864-1.733,.864,1.733c.137,.275,.418,.449,.726,.449,.602,0,.994-.634,.726-1.173l-1.409-2.827,1.409-2.827c.269-.539-.123-1.173-.726-1.173Zm7.009,10.992c0,.553-.447,1-1,1H5c-2.757,0-5-2.243-5-5V5C0,2.243,2.243,0,5,0h4.515c1.869,0,3.627,.728,4.95,2.05l3.484,3.486c.888,.887,1.521,2,1.833,3.217,.076,.299,.011,.617-.179,.861s-.481,.387-.79,.387h-5.813c-1.654,0-3-1.346-3-3V2.023c-.16-.015-.322-.023-.485-.023H5c-1.654,0-3,1.346-3,3v14c0,1.654,1.346,3,3,3h14c.553,0,1,.447,1,1ZM12,7c0,.551,.448,1,1,1h4.339c-.22-.382-.489-.736-.804-1.05l-3.484-3.486c-.318-.318-.671-.587-1.051-.805V7Z" />
                        </svg>
                        Export
                    </div>
                </a>
                <a href="{{ route('peminjaman.print.id', $dataTransaksi->id) }}" target="_blank">
                    <div
                        class="inline-flex gap-x-2 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-fuchsia-500 hover:bg-fuchsia-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 duration-300 transition-all">
                        <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline"
                            viewBox="0 0 24 24" width="512" height="512">
                            <path
                                d="M19,6V4a4,4,0,0,0-4-4H9A4,4,0,0,0,5,4V6a5.006,5.006,0,0,0-5,5v5a5.006,5.006,0,0,0,5,5,3,3,0,0,0,3,3h8a3,3,0,0,0,3-3,5.006,5.006,0,0,0,5-5V11A5.006,5.006,0,0,0,19,6ZM7,4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V6H7ZM17,21a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V17a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Zm5-5a3,3,0,0,1-3,3V17a3,3,0,0,0-3-3H8a3,3,0,0,0-3,3v2a3,3,0,0,1-3-3V11A3,3,0,0,1,5,8H19a3,3,0,0,1,3,3Z" />
                            <path d="M18,10H16a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                        </svg>
                        Print
                    </div>
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-6">
                    <div class="flex flex-col space-y-4 mb-5">
                        <div class="flex justify-between border-b pb-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-x-8">
                                    <div>
                                        <h2 class="text-gray-500 text-sm font-medium">Detail Pengguna</h2>
                                        <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                            {{ "{$dataTransaksi->user_id} - {$dataTransaksi->name}" }}
                                        </h1>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Nomor Telepon</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$dataTransaksi->telp}" }}
                                    </h1>
                                </div>
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Prodi Pengguna</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$dataTransaksi->prodi}" }}
                                    </h1>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Tanggal Pinjam</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ $dataTransaksi->loans->first()?->created_at?->format('d/m/Y') ?? '-' }}
                                    </h1>
                                </div>
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Laboran</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ $dataTransaksi->loans->first()?->creator?->name ?? '-' }}
                                    </h1>
                                </div>
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Keterangan Peminjaman</h2>
                                    <h1 class="text-lg max-w-64 font-semibold items-baseline text-gray-900">
                                        {{ "{$dataTransaksi->detail}" }}
                                    </h1>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-gray-500 text-sm font-medium">Diupdate</h2>
                                <h1 class="text-lg font-semibold text-gray-900">
                                    {{ ($dataTransaksi->loans->sortByDesc('updated_at')->first()?->updater?->name ?? $dataTransaksi->updater->name) . ' | ' . $dataTransaksi->updated_at->format('d/m/Y H:i') }}
                                </h1>
                            </div>
                            <div>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="pb-1">
                        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            @php
                                $columns = [
                                    'product_code' => 'Kode Barang',
                                    'product_name' => 'Nama Barang',
                                    'product_detail' => 'Keterangan',
                                    'merk' => 'Merk',
                                    'quantity' => 'Banyaknya',
                                    'returned_quantity' => 'Dikembalikan',
                                    'damaged_quantity' => 'Rusak',
                                    'status' => 'Status',
                                    'return_date' => 'Tanggal Kembali',
                                    'updated_by' => 'Laboran',
                                    'notes' => 'Catatan',
                                ];
                            @endphp
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="px-1 text-center py-3">
                                        No
                                    </th>
                                    @foreach ($columns as $field => $name)
                                        <th scope="col" class="py-3">
                                            <div class="flex items-center">
                                                {{ $name }}
                                                @php
                                                    // Tentukan arah sorting berdasarkan field yang sedang diurutkan
                                                    $newSortOrder =
                                                        request('sort_field') === $field &&
                                                        request('sort_order') === 'asc'
                                                            ? 'desc'
                                                            : 'asc';
                                                    $isActive = request('sort_field', 'product_name') === $field;
                                                @endphp
                                                <a title="Sort by {{ $name }}"
                                                    href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) }}">
                                                    <svg class="w-3 h-3 ms-1.5 {{ $isActive ? 'fill-uinOrange' : 'fill-white' }}"
                                                        xmlns="http://www.w3.org/2000/svg" id="arrow-circle-down"
                                                        viewBox="0 0 24 24" width="512" height="512">
                                                        <path
                                                            d="M18.873,11.021H5.127a2.126,2.126,0,0,1-1.568-3.56L10.046.872a2.669,2.669,0,0,1,3.939.034l6.431,6.528a2.126,2.126,0,0,1-1.543,3.587ZM12,24.011a2.667,2.667,0,0,1-1.985-.887L3.584,16.6a2.125,2.125,0,0,1,1.543-3.586H18.873a2.125,2.125,0,0,1,1.568,3.558l-6.487,6.589A2.641,2.641,0,0,1,12,24.011Z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </th>
                                    @endforeach
                                    <th scope="col" class="px-1 text-center py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = ($products->currentPage() - 1) * $products->perPage() + 1;
                                @endphp
                                @forelse ($products as $product)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="px-1 text-center py-2">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $product->product_code }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $product->asset->product_name }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ empty($product->asset->product_detail) ? '-' : $product->asset->product_detail }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ empty($product->asset->merk) ? '-' : $product->asset->merk }}
                                        </td>
                                        <td class="px-1 py-2 font-bold text-gray-600">
                                            {{ $product->quantity }} {{ $product->asset->product_unit }}
                                        </td>
                                        @php
                                            $isReturned = $product->status == 'dikembalikan';
                                            $isPartiallyReturned =
                                                $product->returned_quantity > 0 &&
                                                $product->returned_quantity < $product->quantity;
                                            $isZeroDamaged = $product->damaged_quantity == 0;

                                            // Tentukan warna status berdasarkan kondisi pengembalian
                                            if ($isReturned) {
                                                $returnedColor = 'bg-green-200 text-green-700';
                                            } elseif ($isPartiallyReturned) {
                                                $returnedColor = 'bg-yellow-200 text-yellow-700';
                                            } else {
                                                $returnedColor = 'bg-red-200 text-red-700';
                                            }

                                            // Warna untuk jumlah barang rusak
                                            $damagedColor = $isZeroDamaged
                                                ? 'bg-green-200 text-green-700'
                                                : 'bg-red-200 text-red-700';
                                        @endphp
                                        <td scope="row" class="px-1 py-2 font-bold whitespace-nowrap">
                                            <span
                                                class="{{ $returnedColor }} px-2 py-1 rounded-full">{{ $product->returned_quantity . '/' . $product->quantity }}</span>
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-bold whitespace-nowrap">
                                            <span
                                                class="{{ $damagedColor }} px-2 py-1 rounded-full">{{ $product->damaged_quantity }}</span>
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-bold whitespace-nowrap">
                                            <span
                                                class="{{ $returnedColor }} px-2 py-1 rounded-full capitalize">{{ $product->status }}</span>
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-bold whitespace-nowrap">
                                            <span
                                                class="{{ $returnedColor }} px-2 py-1 rounded-full">{{ $product->return_date ?: '-' }}</span>
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-bold whitespace-nowrap">
                                            <span
                                                class="{{ $returnedColor }} px-2 py-1 rounded-full">{{ $isReturned || $isPartiallyReturned ? $product->updater->name : '-' }}</span>
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-bold whitespace-nowrap">
                                            {{ $product->notes ?: ($product->return_notes ?: '-') }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-bold text-center whitespace-nowrap">
                                            <button title="Kembalikan Barang"
                                                @click="openModal({{ $product->id }}, '{{ $product->asset->product_code }}', '{{ $product->asset->product_name }}', {{ $product->quantity }}, {{ $product->returned_quantity }}, {{ $product->damaged_quantity }}, '{{ $product->asset->product_detail }}', '{{ $product->asset->merk }}')"
                                                class="inline-flex items-center p-2 text-sm font-medium rounded-lg shadow-sm text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                                                <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                                    id="Capa_1" x="0px" y="0px" viewBox="0 0 507.506 507.506"
                                                    style="enable-background:new 0 0 507.506 507.506;"
                                                    xml:space="preserve" width="512" height="512">
                                                    <g>
                                                        <path
                                                            d="M163.865,436.934c-14.406,0.006-28.222-5.72-38.4-15.915L9.369,304.966c-12.492-12.496-12.492-32.752,0-45.248l0,0   c12.496-12.492,32.752-12.492,45.248,0l109.248,109.248L452.889,79.942c12.496-12.492,32.752-12.492,45.248,0l0,0   c12.492,12.496,12.492,32.752,0,45.248L202.265,421.019C192.087,431.214,178.271,436.94,163.865,436.934z" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @empty
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th colspan="10" class="px-6 py-2 text-center">
                                            Data Tidak Ditemukan
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Pengembalian --}}
        <div x-show="openEdit" class="fixed z-50 inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"
            @click="openEdit = false">
            <div class="bg-white rounded-2xl p-5 w-96 shadow-lg" @click.stop>
                <h2 class="text-xl font-bold py-5">📝 Form Pengembalian Barang</h2>
                <p class="text-sm font-medium text-gray-900 mt-3">Produk:</p>
                <p class="text-base text-gray-900 font-bold mb-3"><span
                        x-text="selectedProductCode + ' ' + selectedProductName + ' ' + selectedProductMerk + ' ' + selectedProductDetail"></span>
                </p>
                <form method="POST" :action="'{{ route('transaction.return', '') }}/' + selectedProduct">
                    @csrf
                    @method('PUT')
                    <div class="mt-4">
                        <x-input-label for="returned_quantity" :value="__('Jumlah Dikembalikan')" />
                        <x-text-input id="returned_quantity" class="block mt-1 w-full" type="Number"
                            name="returned_quantity" :value="old('returned_quantity')" required autofocus placeholder="0"
                            autocomplete="returned_quantity" x-bind:max="selectedProductQuantity" min="0"
                            x-model="selectedReturnedQuantity" />
                        <x-input-error :messages="$errors->get('returned_quantity')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="damaged_quantity" :value="__('Jumlah Rusak')" />
                        <x-text-input id="damaged_quantity" class="block mt-1 w-full" type="Number"
                            name="damaged_quantity" :value="old('damaged_quantity')" required autofocus placeholder="0"
                            autocomplete="damaged_quantity" x-bind:max="selectedProductQuantity" min="0"
                            x-model="selectedDamagedQuantity" />
                        <x-input-error :messages="$errors->get('damaged_quantity')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="return_notes" :value="__('Catatan')" />
                        <x-text-input id="return_notes" class="block mt-1 w-full" type="text" name="return_notes"
                            :value="old('return_notes')" autofocus placeholder="Catatan" autocomplete="return_notes" />
                        <x-input-error :messages="$errors->get('return_notes')" class="mt-2" />
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-uinRed hover:bg-red-700 text-white rounded-lg transition-all duration-300">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded-lg transition-all duration-300">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
