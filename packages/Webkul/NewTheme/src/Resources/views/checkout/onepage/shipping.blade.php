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
        <div class="mb-7 max-md:mb-0">
            <template v-if="! methods">
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <x-shop::accordion class="overflow-hidden !border-b-0 max-md:rounded-lg">
                    <x-slot:header class="px-0 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-medium">@lang('shop::app.checkout.onepage.shipping.shipping-method')</h2>
                        </div>
                    </x-slot>

                    <x-slot:content class="mt-8 !p-0">
                        <div class="flex flex-wrap gap-8">
                            <div 
                                v-for="method in methods" 
                                class="flex flex-wrap gap-8 w-full"
                            >
                                <div
                                    class="relative max-w-[218px] select-none w-full"
                                    v-for="rate in method.rates"
                                >
                                    <input 
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="peer hidden"
                                        :checked="rate.method == selectedMethod"
                                        @change="store(rate.method)"
                                    >

                                    <label :for="rate.method" class="icon-radio-unselect peer-checked:icon-radio-select absolute top-5 cursor-pointer text-2xl text-navyBlue ltr:right-5 rtl:left-5"></label>

                                    <label :for="rate.method" class="block cursor-pointer rounded-xl border border-zinc-200 p-5">
                                        <template v-if="rate.image">
                                            <img :src="rate.image" class="max-h-20 max-w-30" />
                                        </template>
                                        <p class="mt-1.5 text-2xl font-semibold">@{{ rate.base_formatted_price }}</p>
                                        <p class="mt-2.5 text-xs font-medium">@{{ rate.method_title }}</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <v-inpost-widget 
                            v-if="selectedMethod && selectedMethod.includes('custom_inpostpaczkomaty_shipping')"
                            :method="selectedMethod"
                        ></v-inpost-widget>
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
                };
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
                }
            }
        });

        app.component('v-inpost-widget', {
            template: `
                <div class="mt-6 p-4 border rounded-lg bg-gray-50">
                    <h3 class="mb-4 font-bold">Wybierz swój Paczkomat:</h3>
                    <div ref="geowidgetContainer" style="height:400px; width:100%;"></div>
                    
                    <div v-if="selectedLocker" class="mt-4 p-3 bg-navyBlue text-white rounded shadow">
                        <strong>Wybrany punkt:</strong> @{{ selectedLocker }}
                    </div>
                    <div v-else class="mt-4 p-3 bg-yellow-100 text-yellow-800 rounded">
                        Proszę wybrać paczkomat na mapie.
                    </div>
                </div>
            `,
            props: ['method'],
            data() {
                return {
                    selectedLocker: null,
                    widgetInstance: null
                }
            },
            mounted() {
                this.initWidget();
            },
            methods: {
                initWidget() {
                    const config = {
                        token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
                        language: "pl",
                        config: "parcelcollect"
                    };

                    this.widgetInstance = new InPostGeowidget(config, (station) => {
                        this.selectedLocker = `${station.name}, ${station.address.line1}`;
                        this.saveLockerToDatabase(station);
                    });

                    this.widgetInstance.render(this.$refs.geowidgetContainer);
                },
                saveLockerToDatabase(station) {
                    const details = `${station.name}, ${station.address.line1}, ${station.address.line2}`;
                    
                    // Wywołujemy nasz kontroler, aby zapisać kod paczkomatu w tabeli addresses
                    this.$axios.post("{{ route('inpost.save_paczkomat') }}", {
                        paczkomat_id: station.name,
                        paczkomat_details: details
                    })
                    .then(response => {
                        console.log("Paczkomat zapisany w bazie.");
                    })
                    .catch(error => console.error("Błąd zapisu:", error));
                }
            }
        });
    </script>
@endPushOnce