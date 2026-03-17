{{-- InPost GeoWidget — rendered via View Render Event after shipping method selection --}}

@php
    $cart = \Webkul\Checkout\Facades\Cart::getCart();
    $isInpostSelected = $cart && $cart->shipping_method === 'inpost_inpost';

    // Read the GeoWidget token from system config
    $geowidgetToken = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';

    // Choose JS/CSS URLs based on configured environment
    $environment    = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';
    $widgetBaseUrl  = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';

    // Previously stored selection (back navigation, SPA re-render, etc.)
    $savedPointId      = session('inpost_point_id', '');
    $savedPointAddress = session('inpost_point_address', '');
@endphp

@if ($isInpostSelected)
    {{-- ──────────────────────────────────────────────────────────────────── --}}
    {{-- GeoWidget assets (loaded once per page)                             --}}
    {{-- ──────────────────────────────────────────────────────────────────── --}}
    @once
        <link
            rel="stylesheet"
            href="{{ $widgetBaseUrl }}/inpost-geowidget.css"
        >
        <script defer src="{{ $widgetBaseUrl }}/inpost-geowidget.js"></script>
    @endonce

    {{-- ──────────────────────────────────────────────────────────────────── --}}
    {{-- Wrapper shown below the InPost shipping option                      --}}
    {{-- ──────────────────────────────────────────────────────────────────── --}}
    <div
        id="inpost-widget-wrapper"
        class="mt-4 rounded-lg border border-gray-200 p-4 bg-white shadow-sm"
    >
        <p class="mb-3 font-semibold text-gray-800">
            {{ __('inpost::app.geowidget.select-locker') }}
        </p>

        {{-- ── Selected locker info (hidden until a point is chosen) ── --}}
        <div
            id="inpost-selected-info"
            class="{{ $savedPointId ? '' : 'hidden' }} mb-3 flex items-start gap-3 rounded-md border border-green-300 bg-green-50 p-3"
        >
            {{-- Locker icon --}}
            <span class="mt-0.5 text-2xl">📦</span>

            <div class="flex-1">
                <p class="font-semibold text-green-800" id="inpost-point-name">
                    {{ $savedPointId }}
                </p>
                <p class="text-sm text-green-700" id="inpost-point-address">
                    {{ $savedPointAddress }}
                </p>
            </div>

            <button
                type="button"
                onclick="inpostOpenWidget()"
                class="shrink-0 text-sm text-blue-600 underline hover:text-blue-800"
            >
                {{ __('inpost::app.geowidget.change') }}
            </button>
        </div>

        {{-- ── Open widget button (shown when no point is selected yet) ── --}}
        <button
            type="button"
            id="inpost-open-btn"
            onclick="inpostOpenWidget()"
            class="{{ $savedPointId ? 'hidden' : '' }} inline-flex items-center gap-2 rounded-md bg-yellow-400 px-5 py-2.5 font-semibold text-black hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400"
        >
            📦 {{ __('inpost::app.geowidget.choose-locker') }}
        </button>

        {{-- ── Validation hint (visible only when user tries to proceed without selecting) ── --}}
        <p
            id="inpost-validation-msg"
            class="hidden mt-2 text-sm text-red-600"
        >
            {{ __('inpost::app.geowidget.validation-required') }}
        </p>
    </div>

    {{-- ──────────────────────────────────────────────────────────────────── --}}
    {{-- Modal overlay with the embedded GeoWidget                           --}}
    {{-- ──────────────────────────────────────────────────────────────────── --}}
    <div
        id="inpost-modal"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('inpost::app.geowidget.modal-title') }}"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4"
    >
        <div class="flex h-[90vh] w-full max-w-5xl flex-col rounded-xl bg-white shadow-2xl">

            {{-- Modal header --}}
            <div class="flex items-center justify-between border-b px-5 py-3">
                <span class="text-lg font-bold text-gray-900">
                    📦 {{ __('inpost::app.geowidget.modal-title') }}
                </span>
                <button
                    type="button"
                    onclick="inpostCloseWidget()"
                    aria-label="Zamknij"
                    class="text-3xl leading-none text-gray-500 hover:text-gray-800"
                >&times;</button>
            </div>

            {{-- GeoWidget custom element — InPost renders into this --}}
            <div class="flex-1 overflow-hidden">
                <inpost-geowidget
                    id="inpost-geowidget"
                    token="{{ $geowidgetToken }}"
                    language="pl"
                    config="parcelcollect"
                    onpoint="window.onInpostPointSelected"
                    style="width:100%;height:100%;display:block;"
                ></inpost-geowidget>
            </div>

        </div>
    </div>

    {{-- ──────────────────────────────────────────────────────────────────── --}}
    {{-- JavaScript                                                           --}}
    {{-- ──────────────────────────────────────────────────────────────────── --}}
    <script>
        /**
         * Open the GeoWidget modal.
         */
        function inpostOpenWidget() {
            document.getElementById('inpost-modal').classList.remove('hidden');
        }

        /**
         * Close the GeoWidget modal.
         */
        function inpostCloseWidget() {
            document.getElementById('inpost-modal').classList.add('hidden');
        }

        /**
         * Global callback invoked by the InPost GeoWidget after the customer
         * picks a locker. The `point` object is provided by the widget SDK.
         *
         * @param {Object} point — InPost point object
         */
        window.onInpostPointSelected = function (point) {
            // Close modal immediately
            inpostCloseWidget();

            const pointId   = point.name; // e.g. "WAW123M"
            const pointName = point.name;

            // Build a human-readable address
            const addr = point.address_details ?? {};
            const pointAddress = addr.street
                ? `${addr.street} ${addr.building_number ?? ''}, ${addr.post_code ?? ''} ${addr.city ?? ''}`.trim()
                : (point.address?.line1 ?? '');

            // Update UI
            document.getElementById('inpost-point-name').textContent    = pointName;
            document.getElementById('inpost-point-address').textContent = pointAddress;
            document.getElementById('inpost-selected-info').classList.remove('hidden');
            document.getElementById('inpost-open-btn').classList.add('hidden');
            document.getElementById('inpost-validation-msg').classList.add('hidden');

            // Persist selection to the backend via AJAX
            fetch('{{ route('inpost.save-point') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                    ?? '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    point_id:      pointId,
                    point_name:    pointName,
                    point_address: pointAddress,
                }),
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (! data.success) {
                    console.error('InPost: save-point endpoint returned error', data);
                }
            })
            .catch(function (err) {
                console.error('InPost: network error while saving point', err);
            });
        };

        /**
         * Re-validate before checkout form submit — prevent proceeding
         * if InPost is selected but no locker was chosen.
         *
         * Works both with Bagisto's Vue-driven checkout and plain forms.
         */
        (function () {
            function isInpostSelected() {
                // Bagisto's default checkout uses a radio with name="shipping_method"
                const radio = document.querySelector('input[name="shipping_method"]:checked');
                return radio && radio.value === 'inpost_inpost';
            }

            function isLockerChosen() {
                const info = document.getElementById('inpost-selected-info');
                return info && ! info.classList.contains('hidden');
            }

            // Hook into any "place order" / "continue" buttons
            document.addEventListener('click', function (e) {
                const btn = e.target.closest(
                    '[data-checkout-submit], .place-order-btn, #place-order'
                );
                if (! btn) return;

                if (isInpostSelected() && ! isLockerChosen()) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    document.getElementById('inpost-validation-msg').classList.remove('hidden');
                    document.getElementById('inpost-widget-wrapper').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                    });
                }
            }, true);
        })();
    </script>
@endif
