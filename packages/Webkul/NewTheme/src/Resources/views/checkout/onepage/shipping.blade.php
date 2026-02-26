{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.before') !!}

<link rel="stylesheet" href="https://sdk.inpost.pl/geowidget/v1/assets/css/geowidget.css">
<script src="https://sdk.inpost.pl/geowidget/v1/assets/js/geowidget.js" defer></script>

<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
></v-shipping-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.after') !!}

@pushOnce('scripts')
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
                                <div v-for="rate in method.rates" class="relative">
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
                                        <p>@{{ rate.base_formatted_price }}</p>
                                    </label>
                                </div>
                            </template>
                        </div>

                        <div v-show="isInPostSelected" class="mt-8 p-4 border rounded-lg bg-gray-50">
                            <h3 class="font-bold mb-2">Wybierz Paczkomat:</h3>
                            <div id="geowidget-container" ref="inpostMap" style="height: 400px; width: 100%;"></div>
                            
                            <div v-if="selectedLocker" class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                                <strong>Wybrany punkt:</strong> @{{ selectedLocker }}
                            </div>
                        </div>
                    </x-slot>
                </x-shop::accordion>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',

            props: ['methods'],

            data() {
                return {
                    selectedMethod: "{{ cart()->getCart() ? cart()->getCart()->shipping_method : '' }}",
                    selectedLocker: null,
                    widgetInstance: null
                };
            },

            computed: {
                // Czy wybrano metodę InPost?
                isInPostSelected() {
                    return this.selectedMethod && this.selectedMethod.includes('custom_inpostpaczkomaty_shipping');
                }
            },

            watch: {
                // Reagujemy na zmianę metody
                selectedMethod(newVal) {
                    if (this.isInPostSelected) {
                        this.$nextTick(() => this.initInPost());
                    }
                }
            },

            mounted() {
                if (this.isInPostSelected) {
                    this.initInPost();
                }
            },

            methods: {
                store(method) {
                    this.selectedMethod = method;
                    this.$emit('processing', 'payment');

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {
                            shipping_method: method
                        })
                        .then(response => {
                            this.$emit('processed', 'payment');
                        })
                        .catch(error => {
                            this.$emit('processed', 'payment');
                        });
                },

                initInPost() {
                    if (this.widgetInstance) return;

                    const config = {
                        token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
                        language: "pl",
                        config: "parcelcollect"
                    };

                    this.widgetInstance = new InPostGeowidget(config, (station) => {
                        this.selectedLocker = `${station.name}, ${station.address.line1}`;
                        
                        // Zapis do bazy
                        this.$axios.post("{{ route('inpost.save_paczkomat') }}", {
                            paczkomat_id: station.name,
                            paczkomat_details: this.selectedLocker
                        });
                    });

                    this.widgetInstance.render(this.$refs.inpostMap);
                }
            }
        });
    </script>
@endPushOnce