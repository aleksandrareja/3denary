{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.before') !!}

<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Shipping Method Shimmer Effect -->
    <x-shop::shimmer.checkout.onepage.shipping-method />
</v-shipping-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shipping-methods-template"
    >
        <div class="mb-7 max-md:mb-0">
            <template v-if="! methods">
                <!-- Shipping Method Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <!-- Accordion Blade Component -->
                <x-shop::accordion class="overflow-hidden !border-b-0 max-md:rounded-lg max-md:!border-none max-md:!bg-gray-100">
                    <!-- Accordion Blade Component Header -->
                    <x-slot:header class="px-0 py-4 max-md:p-3 max-md:text-sm max-md:font-medium max-sm:p-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-medium max-md:text-base">
                                @lang('shop::app.checkout.onepage.shipping.shipping-method')
                            </h2>
                        </div>
                    </x-slot>

                    <!-- Accordion Blade Component Content -->
                    <x-slot:content class="mt-8 !p-0 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
                        <div class="flex flex-wrap gap-8 max-md:gap-4 max-sm:gap-2.5">
                            <template v-for="method in methods">
                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping_method.before') !!}

                                <div
                                    class="relative max-w-[218px] select-none max-md:max-w-full max-md:flex-auto"
                                    v-for="rate in method.rates"
                                >
                                    <input 
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="peer hidden"
                                        @change="store(rate.method)"
                                    >

                                    <label 
                                        class="icon-radio-unselect peer-checked:icon-radio-select absolute top-5 cursor-pointer text-2xl text-navyBlue ltr:right-5 rtl:left-5"
                                        :for="rate.method"
                                    >
                                    </label>
                                    

                                    <label 
                                        class="block cursor-pointer rounded-xl border border-zinc-200 p-5 max-sm:flex max-sm:gap-4 max-sm:rounded-lg max-sm:px-4 max-sm:py-2.5"
                                        :for="rate.method"
                                    >
                                        <template v-if="rate.image">
                                            <img
                                                :src="rate.image"
                                                :alt="rate.method_title"
                                                class="max-h-20 max-w-[120px] object-contain mb-2"
                                            />
                                        </template>

                                        <template v-else>
                                            <span class="icon-flate-rate text-6xl text-navyBlue max-sm:text-5xl"></span>
                                        </template>

                                        <div>
                                            <p class="mt-1.5 text-2xl font-semibold max-md:text-base">
                                                @{{ rate.base_formatted_price }}
                                            </p>
                                            
                                            <p class="mt-2.5 text-xs font-medium max-md:mt-1 max-sm:mt-0 max-sm:font-normal max-sm:text-zinc-500">
                                                <span class="font-medium">@{{ rate.method_title }}</span> - @{{ rate.method_description }}
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping_method.after') !!}
                            </template>
                        </div>
                    </x-slot>
                </x-shop::accordion>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
            },

            emits: ['processing', 'processed'],

            methods: {
                store(selectedMethod) {
                    this.$emit('processing', 'payment');

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {    
                            shipping_method: selectedMethod,
                        })
                        .then(response => {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                this.$emit('processed', response.data.payment_methods);
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'shipping');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce

@php
    $geowidgetToken = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';
    $environment    = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';
    $widgetBaseUrl  = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';
@endphp

<link rel="stylesheet" href="{{ $widgetBaseUrl }}/inpost-geowidget.css">
<script defer src="{{ $widgetBaseUrl }}/inpost-geowidget.js"></script>

<div id="inpost-widget-wrapper" style="display:none" class="mt-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
    <p class="mb-3 font-semibold text-gray-800">Wybierz paczkomat InPost</p>
    <div id="inpost-selected-info" class="hidden mb-3 flex items-start gap-3 rounded-md border border-green-300 bg-green-50 p-3">
        <span class="mt-0.5 text-2xl">📦</span>
        <div class="flex-1">
            <p class="font-semibold text-green-800" id="inpost-point-name"></p>
            <p class="text-sm text-green-700" id="inpost-point-address"></p>
        </div>
        <button type="button" onclick="inpostOpenWidget()" class="shrink-0 text-sm text-blue-600 underline hover:text-blue-800">Zmień paczkomat</button>
    </div>
    <button type="button" id="inpost-open-btn" onclick="inpostOpenWidget()" class="inline-flex items-center gap-2 rounded-md bg-yellow-400 px-5 py-2.5 font-semibold text-black hover:bg-yellow-500">
        📦 Wybierz paczkomat
    </button>
    <p id="inpost-validation-msg" class="mt-2 hidden text-sm text-red-600">Proszę wybrać paczkomat przed kontynuowaniem</p>
</div>

<div id="inpost-modal" style="display:none" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4">
    <div class="flex h-[90vh] w-full max-w-5xl flex-col rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-5 py-3">
            <span class="text-lg font-bold text-gray-900">📦 Wybierz paczkomat InPost</span>
            <button type="button" onclick="inpostCloseWidget()" class="text-3xl leading-none text-gray-500 hover:text-gray-800">X</button>
        </div>
        <div class="flex-1 overflow-hidden" id="inpost-geowidget-container"></div>
    </div>
</div>

@push('scripts')
<script type="module">
(function () {
    var TOKEN = '{{ $geowidgetToken }}';
    var METHOD = 'inpost_inpost';

    window.inpostOpenWidget = function () {
        document.getElementById('inpost-modal').style.display = 'flex';
        var c = document.getElementById('inpost-geowidget-container');
        if (c && !c.hasChildNodes()) {
            var w = document.createElement('inpost-geowidget');
            w.setAttribute('token', TOKEN);
            w.setAttribute('language', 'pl');
            w.setAttribute('config', 'parcelcollect');
            w.setAttribute('onpoint', 'window.onInpostPointSelected');
            w.style.cssText = 'width:100%;height:100%;display:block;';
            c.appendChild(w);
        }
    };

    window.inpostCloseWidget = function () {
        document.getElementById('inpost-modal').style.display = 'none';
    };

    window.onInpostPointSelected = function (point) {
        inpostCloseWidget();
        var pointId = point.name;
        var addr = point.address_details || {};
        var pointAddress = addr.street
            ? (addr.street + ' ' + (addr.building_number || '') + ', ' + (addr.post_code || '') + ' ' + (addr.city || '')).trim()
            : ((point.address && point.address.line1) || '');

        document.getElementById('inpost-point-name').textContent = pointId;
        document.getElementById('inpost-point-address').textContent = pointAddress;
        document.getElementById('inpost-selected-info').classList.remove('hidden');
        document.getElementById('inpost-open-btn').classList.add('hidden');
        document.getElementById('inpost-validation-msg').classList.add('hidden');

        fetch('{{ route('inpost.save-point') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}',
            },
            body: JSON.stringify({ point_id: pointId, point_name: pointId, point_address: pointAddress }),
        }).then(function(r) { return r.json(); }).catch(function(e) { console.error(e); });
    };

    document.addEventListener('change', function (e) {
        if (!e.target || e.target.name !== 'shipping_method') return;
        document.getElementById('inpost-widget-wrapper').style.display =
            e.target.value === METHOD ? 'block' : 'none';
    });

    function checkInitial() {
        var r = document.querySelector('input[name="shipping_method"]:checked');
        if (r && r.value === METHOD) document.getElementById('inpost-widget-wrapper').style.display = 'block';
    }

    var obs = new MutationObserver(checkInitial);
    obs.observe(document.body, { childList: true, subtree: true });
    setTimeout(function() { obs.disconnect(); }, 15000);
    checkInitial();
})();
</script>
@endpush