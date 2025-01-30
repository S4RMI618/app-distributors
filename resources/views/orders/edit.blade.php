<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Orden') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-4 py-5 sm:px-6">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Información del Cliente (Solo lectura) -->
                        <div>
                            <x-input-label for="customer_info" :value="__('Cliente')" />
                            <input type="text" id="customer_info" 
                                class="block mt-1 w-full rounded-md bg-gray-100" 
                                value="{{ $order->customer->full_name }} - {{ $order->customer->identification }}" 
                                readonly />
                            <input type="hidden" name="customer_id" value="{{ $order->customer->id }}">
                        </div>
                        
                        {{-- <!-- Estado de la Orden -->
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Estado de la Orden')" />
                            <select id="status" name="status" 
                                class="block mt-1 w-full rounded-md border-gray-300">
                                <option value="pendiente" {{ $order->status === 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="facturado" {{ $order->status === 'facturado' ? 'selected' : '' }}>
                                    Facturado
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div> --}}

                        <!-- Buscador de Productos -->
                        <div class="mt-4">
                            <x-input-label for="product_search" :value="__('Buscar Productos')" />
                            <input type="text" id="product_search" name="product_search"
                                class="block mt-1 w-full rounded-md" placeholder="Buscar productos..."
                                oninput="filterProducts()" />
                            <div id="product_list" class="mt-2 border border-gray-200 rounded-md overflow-hidden"></div>
                        </div>

                        <!-- Detalles del Producto Seleccionado -->
                        <div id="selected_product_details" class="mt-4 hidden">
                            <h3 class="text-lg font-semibold">Detalles del Producto</h3>
                            <div class="p-4 bg-gray-100 rounded-md">
                                <p id="product_name" class="font-medium"></p>
                                <p id="product_code"></p>
                                <p id="product_price"></p>
                                <button type="button" id="add_product_button"
                                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md">Agregar Producto</button>
                            </div>
                        </div>

                        <!-- Selección de productos y cantidades -->
                        <div class="mt-4">
                            <x-input-label for="selected_products" :value="__('Productos Seleccionados')" />
                            <div id="selected_products_list" class="block mt-1 w-full rounded-md">
                                <!-- Los productos existentes se cargarán aquí -->
                            </div>
                        </div>

                        <template id="product-template">
                            <div class="product-item flex flex-wrap justify-between items-center mt-2 p-2 bg-gray-100 rounded-md">
                                <span class="product-name font-medium w-full text-center text-1xl bg-gray-200 rounded-md p-1"></span>
                                <div class="flex w-full items-center justify-between md:justify-around gap-4 py-2">
                                    <div class="flex items-center">
                                        <x-input-label for="quantity" class="mr-1">Cantidad:</x-input-label>
                                        <input type="number" name="quantities[]"
                                            class="quantity-input block w-12 md:w-14 rounded-md border border-gray-300 px-2 py-1 tex-center"
                                            min="1" value="1" onchange="updateTotals()">
                                    </div>
                                    <div class="product-subtotal font-medium">
                                        Valor: $<span class="subtotal-amount">0.00</span>
                                    </div>
                                    <input type="hidden" name="products[]" class="product-input">
                                    <input type="hidden" name="product_prices[]" class="product-price-input">
                                    <input type="hidden" name="selected_price_type[]" class="product-price-type-input">
                                    <input type="hidden" class="product-tax-rate" value="">
                                    <input type="hidden" class="product-base-price" value="">
                                    <button type="button"
                                        class="remove-product text-red-600 hover:text-white hover:bg-red-700 rounded-md p-2 py-1 ml-4"
                                        onclick="removeProduct(this)">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Sección de Observaciones -->
                        <div class="mt-4">
                            <x-input-label for="observations" :value="__('Observaciones')" />
                            <textarea id="observations" name="observations" 
                                class="block mt-1 w-full rounded-md p-1 px-2 md:p-4" 
                                rows="3">{{ $order->observations }}</textarea>
                            <x-input-error :messages="$errors->get('observations')" class="mt-2" />
                        </div>

                        <!-- Resumen del total de la orden -->
                        <div id="order_totals" class="mt-6">
                            <x-input-label :value="__('Totales de la Orden')" class="text-center text-xl" />
                            <div class="mt-2 space-y-2 p-4 bg-gray-100 rounded-md">
                                <p id="total_base_price" class="font-semibold">Subtotal: $<span>0.00</span></p>
                                <p id="total_tax" class="font-semibold">Impuestos: $<span>0.00</span></p>
                                <p id="total_price" class="font-bold">Total Final: $<span>0.00</span></p>
                            </div>
                        </div>

                        <div class="flex w-full items-center justify-center mt-6">
                            <x-primary-button type="submit">{{ __('Actualizar Orden') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedProduct = null;

        // Cargar los productos existentes de la orden
        document.addEventListener('DOMContentLoaded', function() {
            const orderProducts = @json($order->products);
            orderProducts.forEach(product => {
                const orderDetail = product.pivot;
                console.log(product, orderDetail);
                addExistingProductToList(product, orderDetail);
            });
            updateTotals();
        });

        function addExistingProductToList(product, orderDetail) {
            let template = document.getElementById('product-template').content.cloneNode(true);
            
            template.querySelector('.product-name').textContent = `${product.code} - ${product.name}`;
            template.querySelector('.product-input').value = product.id;
            template.querySelector('.quantity-input').value = orderDetail.quantity;
            template.querySelector('.product-price-input').value = orderDetail.price_final;
            template.querySelector('.product-base-price').value = orderDetail.price_final;
            template.querySelector('.product-tax-rate').value = product.tax_rate;
            template.querySelector('.product-price-type-input').value = orderDetail.price_final;

            document.getElementById('selected_products_list').appendChild(template);
        }

        function filterProducts() {
            let query = document.getElementById('product_search').value;
            const productsRoute = @json(route('products.search'));

            if (query.length > 1) {
                fetch(`${productsRoute}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let productList = document.getElementById('product_list');
                        productList.innerHTML = '';

                        if (data.length === 0) {
                            let div = document.createElement('div');
                            div.textContent = 'No se encontraron productos';
                            div.classList.add('p-2');
                            productList.appendChild(div);
                            return;
                        }

                        if (data.length === 1) {
                            showProductDetails(data[0]);
                            productList.classList.add('hidden');
                            return;
                        }

                        data.forEach(product => {
                            let div = document.createElement('div');
                            div.textContent = `${product.name} - ${product.code}`;
                            div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
                            div.addEventListener('click', function() {
                                showProductDetails(product);
                                productList.classList.add('hidden');
                            });
                            productList.appendChild(div);
                            productList.classList.remove('hidden');
                        });
                    });
            }
        }
        // Agregar el event listener para el Enter
        document.getElementById('product_search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();

                if (query.length > 0) {
                    const productsRoute = @json(route('products.search'));

                    fetch(`${productsRoute}?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === false) {
                                let div = document.createElement('div');
                                div.textContent = data.message;
                                div.classList.add('p-2');
                                productList.appendChild(div);
                                return;
                            }
                            // Verificar si hay exactamente una coincidencia y es un código exacto
                            const matchedProduct = data.find(product => product.code.toLowerCase() === query
                                .toLowerCase());
                            if (matchedProduct) {
                                selectedProduct = matchedProduct; // Establecer el producto seleccionado, el que necesita la función
                                updateSelectedPrice(parseFloat(selectedProduct.base_price_1), selectedProduct.tax_rate, 1);
                                addProductToList();
                                productDefault.classList.add('hidden');
                                this.value = ''; // Limpiar el input
                                document.getElementById('product_list').innerHTML = '';
                            }
                        });
                }
            }
        });

        function showProductDetails(product) {
            selectedProduct = product;
            const base_price_1 = parseFloat(product.base_price_1);
            const base_price_2 = parseFloat(product.base_price_2);
            const base_price_3 = parseFloat(product.base_price_3);
            const tax_rate = parseFloat(product.tax_rate);

            selectedProduct.selected_base_price = product.base_price_1;
            selectedProduct.selected_price_type = 1;

            let pricesHTML = '<div class="mt-2 space-y-2">';

            if (base_price_1) {
                pricesHTML += `
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="price1" name="selected_price" value="1" 
                            class="form-radio" checked 
                            onchange="updateSelectedPrice(${product.base_price_1}, ${product.tax_rate}, 1)">
                        <label for="price1">Precio 1: $${(base_price_1 + (base_price_1 * tax_rate / 100)).toFixed(2)}</label>
                    </div>`;
            }

            if (base_price_2) {
                pricesHTML += `
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="price2" name="selected_price" value="2" 
                            class="form-radio" 
                            onchange="updateSelectedPrice(${product.base_price_2}, ${product.tax_rate}, 2)">
                        <label for="price2">Precio 2: $${(base_price_2 + (base_price_2 * tax_rate / 100)).toFixed(2)}</label>
                    </div>`;
            }

            if (base_price_3) {
                pricesHTML += `
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="price3" name="selected_price" value="3" 
                            class="form-radio" 
                            onchange="updateSelectedPrice(${product.base_price_3}, ${product.tax_rate}, 3)">
                        <label for="price3">Precio 3: $${(base_price_3 + (base_price_3 * tax_rate / 100)).toFixed(2)}</label>
                    </div>`;
            }

            pricesHTML += '</div>';

            document.getElementById('product_name').textContent = `Nombre: ${product.name}`;
            document.getElementById('product_code').textContent = `Código: ${product.code}`;
            document.getElementById('product_price').innerHTML = pricesHTML;

            document.getElementById('selected_product_details').classList.remove('hidden');
        }

        function updateSelectedPrice(basePrice, taxRate, priceType) {
            selectedProduct.selected_base_price = basePrice;
            selectedProduct.selected_price_type = priceType;
        }

        function addProductToList() {
            if (!selectedProduct) return;

            const existingProduct = findExistingProduct(selectedProduct.selected_base_price);
            if (existingProduct) {
                const quantityInput = existingProduct.querySelector('.quantity-input');
                quantityInput.value = parseInt(quantityInput.value) + 1;
                updateTotals();
            } else {
                let template = document.getElementById('product-template').content.cloneNode(true);

                const selectedBasePrice = selectedProduct.selected_base_price || selectedProduct.base_price_1;
                const selectedPriceType = selectedProduct.selected_price_type || 1;

                template.querySelector('.product-name').textContent = `${selectedProduct.code} - ${selectedProduct.name}`;
                template.querySelector('.product-input').value = selectedProduct.id;
                template.querySelector('.product-price-input').value = selectedBasePrice;
                template.querySelector('.product-base-price').value = selectedBasePrice;
                template.querySelector('.product-tax-rate').value = selectedProduct.tax_rate;
                template.querySelector('.product-price-type-input').value = selectedPriceType;

                document.getElementById('selected_products_list').appendChild(template);
            }

            updateTotals();
            document.getElementById('selected_product_details').classList.add('hidden');
            document.getElementById('product_list').innerHTML = '';
            document.getElementById('product_search').value = '';
            selectedProduct = null;
        }
        // Event listener para el botón de agregar producto
        document.getElementById('add_product_button').addEventListener('click', function() {
            addProductToList();
            updateTotals();
        });

        function findExistingProduct(productId) {
            const productItems = document.querySelectorAll('.product-item');
            for (let item of productItems) {
                const itemProductId = item.querySelector('.product-base-price').value;
                if (itemProductId === productId.toString()) {
                    return item;
                }
            }
            return null;
        }

        function removeProduct(button) {
            button.closest('.product-item').remove();
            updateTotals();
        }

        function updateTotals() {
            let totalBasePrice = 0;
            let totalTax = 0;

            document.querySelectorAll('.product-item').forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
                const basePrice = parseFloat(item.querySelector('.product-base-price').value);
                const taxRate = parseFloat(item.querySelector('.product-tax-rate').value);

                const subtotalBase = basePrice * quantity;
                const subtotalTax = (basePrice * taxRate / 100) * quantity;
                const subtotalTotal = subtotalBase + subtotalTax;

                item.querySelector('.subtotal-amount').textContent = subtotalTotal.toFixed(2);

                totalBasePrice += subtotalBase;
                totalTax += subtotalTax;
            });

            const totalFinal = totalBasePrice + totalTax;
            document.querySelector('#total_base_price span').textContent = totalBasePrice.toFixed(2);
            document.querySelector('#total_tax span').textContent = totalTax.toFixed(2);
            document.querySelector('#total_price span').textContent = totalFinal.toFixed(2);

            // Mostrar u ocultar el div de totales
            document.getElementById('order_totals').style.display =
                document.querySelectorAll('.product-item').length > 0 ? 'block' : 'none';
        }
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el envío del formulario
            }
        })
    </script>

</x-app-layout>
