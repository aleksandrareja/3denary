{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.before') !!}


<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
></v-shipping-methods>

@include('custom-inpost-paczkomaty-shipping::checkout.inpost-widget', ['method' => cart()->getCart() ? cart()->getCart()->shipping_method : null])

{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.after') !!}

@pushOnce('scripts')
    <link rel="stylesheet" href="https://sdk.inpost.pl/geowidget/v1/assets/css/geowidget.css">
    <script src="https://sdk.inpost.pl/geowidget/v1/assets/js/geowidget.js"></script>

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
                                    <label :for="rate.method" class="block cursor-pointer rounded-xl border p-5 peer-checked:border-navyBlue peer-checked:bg-gray-50">
                                        <p class="font-semibold">@{{ rate.method_title }}</p>
                                        <p class="text-navyBlue">@{{ rate.base_formatted_price }}</p>
                                    </label>
                                </div>
                            </template>
                        </div>

                        <div v-if="selectedMethod && selectedMethod.includes('inpost')" class="mt-6 p-4 border rounded-xl bg-gray-50">
                            <h3 class="mb-4 font-bold">Wybierz Paczkomat:</h3>
                            <div id="geowidget" ref="inpostMap" style="height: 400px; width: 100%;"></div>
                            
                            <div v-if="selectedLocker" class="mt-4 p-3 bg-green-600 text-white rounded-lg">
                                Wybrano punkt: <strong>@{{ selectedLocker }}</strong>
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
                }
            },
            watch: {
                selectedMethod(newVal) {
                    if (newVal && newVal.includes('inpost')) {
                        this.$nextTick(() => this.initInPost());
                    }
                }
            },
            mounted() {
                if (this.selectedMethod && this.selectedMethod.includes('inpost')) {
                    this.initInPost();
                }
            },
            methods: {
                store(method) {
                    this.selectedMethod = method;
                    this.$emit('processing', 'payment');
                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {
                        shipping_method: method
                    }).then(res => this.$emit('processed', 'payment'));
                },
                initInPost() {
                    if (this.widgetInstance) return;
                    const config = {
                        token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
                        language: "pl", config: "parcelcollect"
                    };
                    this.widgetInstance = new InPostGeowidget(config, (station) => {
                        this.selectedLocker = station.name + ", " + station.address.line1;
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