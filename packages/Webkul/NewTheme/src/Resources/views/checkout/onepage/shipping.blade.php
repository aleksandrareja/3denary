{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.before') !!}


<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
></v-shipping-methods>

@include('custom-inpost-paczkomaty-shipping::checkout.inpost-widget', ['method' => cart()->getCart() ? cart()->getCart()->shipping_method : null])

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
                    </x-slot>
                </x-shop::accordion>

                <v-inpost-widget :method="selectedMethod" />
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
    </script>
@endPushOnce