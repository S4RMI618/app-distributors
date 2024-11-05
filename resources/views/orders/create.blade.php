<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Orden') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-4 py-5 sm:px-6">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <!-- Buscador interactivo del Cliente -->
                        <div>
                            <x-input-label for="customer_search" :value="__('Buscar Cliente')" />
                            <input type="text" id="customer_search" name="customer_search"
                                class="block mt-1 w-full rounded-md" placeholder="Buscar por nombre o identificación"
                                oninput="filterCustomers()" />
                            <div id="customer_list" class="mt-2 border border-gray-200 rounded-md overflow-hidden">
                            </div>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>

                        <!-- Selección del Cliente -->
                        <input type="hidden" id="customer_id" name="customer_id">

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
                                <!-- Aquí se agregarán los productos seleccionados -->
                                
                            </div>
                        </div>

                        <template id="product-template">
                            <div
                                class="product-item flex flex-wrap justify-between items-center mt-2 p-2 bg-gray-100 rounded-md">
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
                                    <input type="hidden" class="product-tax-rate" value="">
                                    <input type="hidden" class="product-base-price" value="">
                                    <button type="button" class="remove-product text-red-600 hover:text-white hover:bg-red-700 rounded-md p-2 py-1 ml-4"
                                        onclick="updateTotals()">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Sección de Observaciones -->
                        <div class="mt-4">
                            <x-input-label for="observations" :value="__('Observaciones')" />
                            <textarea id="observations" name="observations" class="block mt-1 w-full rounded-md p-1 px-2 md:p-4" rows="3" placeholder="Escribe aquí las observaciones..."></textarea>
                            <x-input-error :messages="$errors->get('observations')" class="mt-2" />
                        </div>

                        <!-- Resumen del total de la orden -->
                        <div id="order_totals" class="mt-6">
                            <x-input-label :value="__('Totales de la Orden')" class="text-center text-xl"/>
                            <div class="mt-2 space-y-2 p-4 bg-gray-100 rounded-md">
                                <p id="total_base_price" class="font-semibold">Subtotal: $<span>0.00</span></p>
                                <p id="total_tax" class="font-semibold">Impuestos: $<span>0.00</span></p>
                                <p id="total_price" class="font-bold">Total Final: $<span>0.00</span></p>
                            </div>
                        </div>


                        <div class="flex w-full items-center justify-center mt-6 ">
                            <x-primary-button type="submit">{{ __('Crear Orden') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para filtrar clientes por nombre o identificación
        function filterCustomers() {
            let query = document.getElementById('customer_search').value;

            const customerRoute = @json(route('customers.search'));
            if (query.length > 1) {
                fetch(`${customerRoute}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let customerList = document.getElementById('customer_list');
                        customerList.innerHTML = ''; // Limpiar lista anterior

                        data.forEach(customer => {
                            let div = document.createElement('div');
                            div.textContent = `${customer.full_name} - ${customer.identification}`;
                            div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
                            div.addEventListener('click', function() {
                                selectCustomer(customer);
                            });
                            customerList.appendChild(div);
                        });
                    });
            }
        }

        // Función para seleccionar cliente
        function selectCustomer(customer) {
            document.getElementById('customer_search').value = `${customer.full_name} - ${customer.identification}`;
            document.getElementById('customer_id').value = customer.id;
            document.getElementById('customer_list').innerHTML = ''; // Limpiar lista
        }

        let selectedProduct = null; // Variable para almacenar el producto seleccionado

        // Función para filtrar productos
        function filterProducts() {
            let query = document.getElementById('product_search').value;
            const productsRoute = @json(route('products.search'));
            
            if (query.length > 1) {
                fetch(`${productsRoute}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let productList = document.getElementById('product_list');
                        productList.innerHTML = ''; // Limpiar lista anterior

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

        // Mostrar los detalles del producto seleccionado
        function showProductDetails(product) {
            selectedProduct = product;
            const basePrice = parseFloat(product.base_price);
            const taxRate = parseFloat(product.tax_rate);
            const totalPrice = basePrice + (basePrice * taxRate / 100);

            document.getElementById('product_name').textContent = `Nombre: ${product.name}`;
            document.getElementById('product_code').textContent = `Código: ${product.code}`;
            document.getElementById('product_price').textContent = `Precio: $${totalPrice.toFixed(2)}`;

            document.getElementById('selected_product_details').classList.remove('hidden');
        }

        // Agregar productos
        document.getElementById('add_product_button').addEventListener('click', function() {
            if (!selectedProduct) return;

            // Verificar si el producto ya existe en la lista
            const existingProduct = findExistingProduct(selectedProduct.id);
            
            if (existingProduct) {
                // Incrementar la cantidad del producto existente
                const quantityInput = existingProduct.querySelector('.quantity-input');
                quantityInput.value = parseInt(quantityInput.value) + 1;
                updateTotals();
            } else {
                // Crear nuevo elemento para el producto
                let template = document.getElementById('product-template').content.cloneNode(true);
                
                // Set product information
                template.querySelector('.product-name').textContent = `${selectedProduct.name} - ${selectedProduct.code}`;
                template.querySelector('.product-input').value = selectedProduct.id;
                template.querySelector('.product-base-price').value = selectedProduct.base_price;
                template.querySelector('.product-tax-rate').value = selectedProduct.tax_rate;
                
                // Agregar event listener para la cantidad
                template.querySelector('.quantity-input').addEventListener('change', updateTotals);
                
                // Agregar event listener para eliminar
                template.querySelector('.remove-product').addEventListener('click', function() {
                    this.closest('.product-item').remove();
                    updateTotals();
                });

                // Agregar al DOM
                document.getElementById('selected_products_list').appendChild(template);
                updateTotals();
            }

            // Limpiar selección
            document.getElementById('selected_product_details').classList.add('hidden');
            document.getElementById('product_search').value = '';
            document.getElementById('product_list').innerHTML = '';
            selectedProduct = null;
        });

        // Nueva función para encontrar un producto existente en la lista
        function findExistingProduct(productId) {
            const productItems = document.querySelectorAll('.product-item');
            for (let item of productItems) {
                const itemProductId = item.querySelector('.product-input').value;
                if (itemProductId === productId.toString()) {
                    return item;
                }
            }
            return null;
        }

        // Nueva función para actualizar todos los totales
        function updateTotals() {
            let totalBasePrice = 0;
            let totalTax = 0;
            
            // Recorrer cada producto en la lista
            document.querySelectorAll('.product-item').forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
                const basePrice = parseFloat(item.querySelector('.product-base-price').value);
                const taxRate = parseFloat(item.querySelector('.product-tax-rate').value);
                
                // Calcular subtotales
                const subtotalBase = basePrice * quantity;
                const subtotalTax = (basePrice * taxRate / 100) * quantity;
                const subtotalTotal = subtotalBase + subtotalTax;
                
                // Actualizar subtotal del producto
                item.querySelector('.subtotal-amount').textContent = subtotalTotal.toFixed(2);
                
                // Acumular totales
                totalBasePrice += subtotalBase;
                totalTax += subtotalTax;
            });

            // Actualizar totales generales
            const totalFinal = totalBasePrice + totalTax;
            document.querySelector('#total_base_price span').textContent = totalBasePrice.toFixed(2);
            document.querySelector('#total_tax span').textContent = totalTax.toFixed(2);
            document.querySelector('#total_price span').textContent = totalFinal.toFixed(2);
            
            // Mostrar u ocultar el div de totales
            document.getElementById('order_totals').style.display = 
                document.querySelectorAll('.product-item').length > 0 ? 'block' : 'none';
        }
    </script>

</x-app-layout>
