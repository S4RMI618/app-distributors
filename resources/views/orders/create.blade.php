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
                                <div id="product_default"
                                    class="flex justify-left items-center p-4 pl-0 border-gray-200 border-b-2 text-gray-500">
                                    <p class="text-center">No hay productos seleccionados!</p>
                                </div>
                                <!-- Aquí se agregarán los productos seleccionados -->

                            </div>
                        </div>

                        <template id="product-template">
                            <div
                                class="product-item flex flex-wrap justify-between items-center mt-2 p-2 bg-gray-100 rounded-md">
                                <span
                                    class="product-name font-medium w-full text-center text-1xl bg-gray-200 rounded-md p-1"></span>
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
                                    <!-- Nuevo campo -->
                                    <input type="hidden" class="product-tax-rate" value="">
                                    <input type="hidden" class="product-base-price" value="">
                                    <button type="button"
                                        class="remove-product text-red-600 hover:text-white hover:bg-red-700 rounded-md p-2 py-1 ml-4"
                                        onclick="updateTotals()">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Sección de Observaciones -->
                        <div class="mt-4">
                            <x-input-label for="observations" :value="__('Observaciones')" />
                            <textarea id="observations" name="observations" class="block mt-1 w-full rounded-md p-1 px-2 md:p-4" rows="3"
                                placeholder="Escribe aquí las observaciones..."></textarea>
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

                        if (data.length === 0) {
                            let div = document.createElement('div');
                            div.textContent = 'No se encontraron clientes';
                            div.classList.add('p-2');
                            customerList.appendChild(div);
                            return;
                        }

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

        let productDefault = document.getElementById('product_default');

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
                                productDefault.classList.add('hidden');
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

        // Mostrar los detalles del producto seleccionado
        function showProductDetails(product) {
            selectedProduct = product;
            const base_price_1 = parseFloat(product.base_price_1);
            const base_price_2 = parseFloat(product.base_price_2);
            const base_price_3 = parseFloat(product.base_price_3);
            const tax_rate = parseFloat(product.tax_rate);

            // Establecer el precio por defecto (precio 1)
            selectedProduct.selected_base_price = product.base_price_1;
            selectedProduct.selected_price_type = 1;

            // Crear el HTML para los radio buttons de precios
            let pricesHTML = '<div class="mt-2 space-y-2">';

            // Precio 1 (siempre debe existir)
            if (base_price_1) {
                pricesHTML += `
            <div class="flex items-center space-x-2">
                <input type="radio" id="price1" name="selected_price" value="1" 
                    class="form-radio" checked 
                    onchange="updateSelectedPrice(${product.base_price_1}, ${product.tax_rate}, 1)">
                <label for="price1">Precio 1: $${(base_price_1 + (base_price_1 * tax_rate / 100)).toFixed(2)}</label>
            </div>`;
            }

            // Precio 2 (opcional)
            if (base_price_2) {
                pricesHTML += `
            <div class="flex items-center space-x-2">
                <input type="radio" id="price2" name="selected_price" value="2" 
                    class="form-radio" 
                    onchange="updateSelectedPrice(${product.base_price_2}, ${product.tax_rate}, 2)">
                <label for="price2">Precio 2: $${(base_price_2 + (base_price_2 * tax_rate / 100)).toFixed(2)}</label>
            </div>`;
            }

            // Precio 3 (opcional)
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

        // Función para agregar producto a la lista
        function addProductToList() {
            if (!selectedProduct) return;
            // Verificar si el producto ya existe
            const existingProduct = findExistingProduct(selectedProduct.selected_base_price);

            if (existingProduct) {
                const quantityInput = existingProduct.querySelector('.quantity-input');
                quantityInput.value = parseInt(quantityInput.value) + 1;
                updateTotals();
            } else {
                let template = document.getElementById('product-template').content.cloneNode(true);

                // Asegurarnos de que tenemos un precio base seleccionado
                const selectedBasePrice = selectedProduct.selected_base_price || selectedProduct.base_price_1;
                const selectedPriceType = selectedProduct.selected_price_type || 1;

                template.querySelector('.product-name').textContent = `${selectedProduct.code} - ${selectedProduct.name}`;
                template.querySelector('.product-input').value = selectedProduct.id;
                template.querySelector('.product-price-input').value = selectedBasePrice;
                template.querySelector('.product-base-price').value = selectedBasePrice;
                template.querySelector('.product-tax-rate').value = selectedProduct.tax_rate;
                // Agregar el tipo de precio seleccionado
                template.querySelector('.product-price-type-input').value = selectedPriceType;

                template.querySelector('.quantity-input').addEventListener('change', updateTotals);
                template.querySelector('.remove-product').addEventListener('click', function() {
                    this.closest('.product-item').remove();
                    updateTotals();
                });

                document.getElementById('selected_products_list').appendChild(template);
                updateTotals();
            }
            /* console.log(selectedProduct); */

            // Limpiar selección
            document.getElementById('selected_product_details').classList.add('hidden');
            document.getElementById('product_list').innerHTML = '';
            document.getElementById('product_search').value = '';
            selectedProduct = null;
        }

        // Función auxiliar para actualizar el precio seleccionado
        function updateSelectedPrice(basePrice, taxRate, priceType) {
            selectedProduct.selected_base_price = basePrice;
            selectedProduct.selected_price_type = priceType;

            // Actualizar el precio mostrado
            const totalPrice = basePrice + (basePrice * taxRate / 100);
            /* console.log('Precio seleccionado:', basePrice, 'Tipo:', priceType); */
        }

        // Event listener para el botón de agregar producto
        document.getElementById('add_product_button').addEventListener('click', function() {
            addProductToList();
            updateTotals();
        });

        // Nueva función para encontrar un producto existente en la lista
        function findExistingProduct(productBasePrice) {
            const productItems = document.querySelectorAll('.product-item');
            for (let item of productItems) {
                const itemProductBasePrice = item.querySelector('.product-base-price').value;
                if (itemProductBasePrice === productBasePrice.toString()) {
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
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el envío del formulario
            }
        })
    </script>

</x-app-layout>
