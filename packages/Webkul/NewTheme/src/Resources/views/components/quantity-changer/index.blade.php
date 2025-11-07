@props([
    'name'     => '',
    'value'    => 1,
    'minValue' => 1,
    'maxValue' => null,
    'product'  => null,  {{-- przekazujemy obiekt produktu --}}
])

@if ($product && $product->attribute_family_id != 2)
    <v-quantity-changer
        {{ $attributes->merge(['class' => 'flex items-center border border-navyBlue']) }}
        name="{{ $name }}"
        value="{{ $value }}"
        min-value="{{ $minValue }}"
        max-value="{{ $maxValue ?? $product->inventories->sum('qty') }}"
    >
    </v-quantity-changer>
@else
    <p class="text-sm text-gray-700">
        Dostępna ilość: 1
    </p>
@endif

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div>
            <span 
                class="icon-minus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
                :class="{ 'opacity-50 cursor-not-allowed': quantity <= minValue }"
            ></span>

            <p class="w-8 select-none text-center">
                @{{ quantity }}
            </p>
            
            <span 
                class="icon-plus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
                :class="{ 'opacity-50 cursor-not-allowed': quantity >= maxValue }"
            ></span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['name', 'value', 'minValue', 'maxValue'],

            data() {
                return  {
                    quantity: this.value,
                }
            },

            watch: {
                value() {
                    this.quantity = this.value;
                },
            },

            methods: {
                increase() {
                    if (!this.maxValue || this.quantity < this.maxValue) {
                        this.$emit('change', ++this.quantity);
                    }
                },

                decrease() {
                    if (this.quantity > this.minValue) {
                        this.quantity -= 1;
                        this.$emit('change', this.quantity);
                    }
                },
            }
        });
    </script>
@endpushOnce
