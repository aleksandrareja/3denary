<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
></v-shipping-methods>

@include('paczkomaty::checkout.inpost-widget')

@pushOnce('scripts')
    <script>console.log('Shipping Blade: Ładowanie szablonu v-shipping-methods...');</script>

    <script type="text/x-template" id="v-shipping-methods-template">
        <div class="mb-7">
            <template v-if="! methods">
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <x-shop::accordion>
                    <x-slot:header>
                        <h2 class="text-2xl font-medium">@lang('shop::app.checkout.onepage.shipping.shipping-method')</h2>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex flex-wrap gap-8">
                            <template v-for="method in methods">
                                <div v-for="rate in method.rates" class="relative max-w-[218px] w-full">
                                    <input 
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="peer hidden"
                                        @change="store(rate.method)"
                                        :checked="rate.method == selectedMethod"
                                    >
                                    <label :for="rate.method" class="block cursor-pointer rounded-xl border p-5 peer-checked:border-navyBlue">
                                        <p class="font-semibold">@{{ rate.method_title }}</p>
                                        <p class="text-navyBlue">@{{ rate.base_formatted_price }}</p>
                                    </label>
                                </div>
                            </template>
                        </div>

                        <v-inpost-widget :method="selectedMethod"></v-inpost-widget>
                        
                    </x-slot:content>
                </x-shop::accordion>
            </template>
        </div>
    </script>

    <script type="module">
        console.log('Shipping Component: Rejestracja v-shipping-methods...');

        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',
            props: ['methods'],
            data() {
                return {
                    selectedMethod: "{{ cart()->getCart() ? cart()->getCart()->shipping_method : '' }}",
                }
            },
            methods: {
                store(method) {
                    console.log('Shipping Component: Wybrano metodę wysyłki:', method);
                    this.selectedMethod = method;
                    
                    this.$emit('processing', 'payment');
                    
                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {
                        shipping_method: method
                    })
                    .then(res => {
                        console.log('Shipping Component: Metoda zapisana na serwerze.');
                        this.$emit('processed', 'payment');
                    })
                    .catch(err => {
                        console.error('Shipping Component: Błąd zapisu metody!', err);
                        this.$emit('processed', 'payment');
                    });
                }
            }
        });
    </script>
@endPushOnce