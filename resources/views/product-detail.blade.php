<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Product Details -->
                    <div class="flex flex-col md:flex-row items-center">
                        <!-- Product Image -->
                        <div class="p-4">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="rounded-lg w-full">
                        </div>

                        <!-- Product Info -->
                        <div class="md:w-1/2 md:pl-8 mt-4 md:mt-0">
                            <h3 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h3>
                            <p class="text-gray-600 mt-4">{{ $product->description }}</p>
                            <p class="text-gray-900 text-xl font-semibold mt-4">
                                Price: IDR {{ number_format($product->price, 2) }}
                            </p>
                            <p class="text-gray-600 mt-2">
                                @foreach ($product->categories as $category)
                                    {{ $category->name }}@if (!$loop->last), @endif
                                @endforeach
                            </p>

                            <!-- Quantity Input -->
                            <div class="mt-6">
                                <label for="quantity" class="text-gray-600">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1"
                                    class="w-20 px-2 py-1 border rounded-md" required>
                            </div>

                            <!-- Buttons -->
                            <div class="mt-6 flex space-x-4">
                                <!-- Add to Wishlist -->
                                <form id="FavouritsForm" method="POST"
                                    action="{{ route('favourit.add', ['product' => $product->id]) }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Wishlist
                                    </button>
                                </form>

                                <!-- Add to Cart -->
                                <form id="addToCartForm" method="POST"
                                    action="{{ route('cart.add', ['product' => $product->id]) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" id="cartQuantity">
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Add to Cart
                                    </button>
                                </form>

                                {{-- <!-- Buy Now -->
                                <form id="buyNowForm" method="POST" action="{{ route('order.confirm') }}">
                                    @csrf
                                    <input type="hidden" name="total_price" id="totalPrice" value="{{ $product->price }}">
                                    <input type="hidden" name="selected_items" id="selectedItems"
                                        value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="buyQuantity">
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Buy Now
                                    </button>
                                </form> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const quantityInput = document.getElementById('quantity');
        const cartQuantityInput = document.getElementById('cartQuantity');
        const buyQuantityInput = document.getElementById('buyQuantity');
        const totalPriceInput = document.getElementById('totalPrice');
        const productPrice = {{ $product->price }};

        // Update dependent inputs dynamically
        quantityInput.addEventListener('input', () => {
            const quantity = parseInt(quantityInput.value) || 1;
            cartQuantityInput.value = quantity;
            buyQuantityInput.value = quantity;
            totalPriceInput.value = productPrice * quantity;
        });

        // Set default values on page load
        cartQuantityInput.value = quantityInput.value;
        buyQuantityInput.value = quantityInput.value;
        totalPriceInput.value = productPrice * parseInt(quantityInput.value);
    </script>
</x-app-layout>
