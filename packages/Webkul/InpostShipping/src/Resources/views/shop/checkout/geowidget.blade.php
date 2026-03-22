@php
    $geowidgetToken    = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';
    $environment       = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';
    $widgetBaseUrl     = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';
    $savedPointId      = session('inpost_point_id', '');
    $savedPointAddress = session('inpost_point_address', '');
@endphp


{{-- Wrapper --}}
<div 
    id="inpost-widget-wrapper" 
    style="display:none;" 
    class="mt-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm"
>
    <h3 class="mb-3 flex items-center gap-2 text-base font-semibold text-zinc-800">
        📦 Wybierz paczkomat
    </h3>

    {{-- WYBRANY PACZKOMAT --}}
    <div 
        id="inpost-selected" 
        class="{{ $savedPointId ? '' : 'hidden' }} flex items-center justify-between rounded-lg bg-green-50 border border-green-200 p-3"
    >
        <div>
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
            class="text-sm font-medium text-navyBlue hover:underline"
        >
            Zmień
        </button>
    </div>

    {{-- BUTTON --}}
    <button
        id="inpost-open-btn"
        type="button"
        onclick="inpostOpenWidget()"
        class="{{ $savedPointId ? 'hidden' : '' }}
               mt-3 w-full rounded-lg bg-yellow-400 px-4 py-2.5 font-semibold text-black
               transition hover:bg-yellow-500 active:scale-[0.99]"
    >
        Wybierz paczkomat
    </button>
</div>

{{-- Modal z GeoWidgetem --}}
<div
    id="inpost-modal"
    class="fixed inset-0 z-[99999] hidden items-center justify-center bg-black/50 backdrop-blur-sm"
>
    <div class="flex h-[85vh] w-[95%] max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

        {{-- HEADER --}}
        <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-4">
            <span class="text-base font-semibold text-zinc-800">
                📦 Wybierz paczkomat InPost
            </span>

            <button
                type="button"
                onclick="inpostCloseWidget()"
                class="rounded-md p-2 text-zinc-500 transition hover:bg-zinc-100 hover:text-black"
            >
                ✕
            </button>
        </div>

        {{-- MAPA --}}
        <div id="inpost-map" class="flex-1"></div>
    </div>
</div>