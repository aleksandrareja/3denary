<!--@php
    $geowidgetToken = core()->getConfigData('sales.carriers.inpost.geowidget_token') ?? '';
    $environment    = core()->getConfigData('sales.carriers.inpost.environment') ?? 'sandbox';
    $widgetBaseUrl  = $environment === 'production'
        ? 'https://geowidget.inpost.pl'
        : 'https://sandbox-geowidget.inpost.pl';

    $savedPointId      = session('inpost_point_id', '');
    $savedPointAddress = session('inpost_point_address', '');
@endphp

@once
    <link rel="stylesheet" href="{{ $widgetBaseUrl }}/inpost-geowidget.css">
    <script src="{{ $widgetBaseUrl }}/inpost-geowidget.js" defer></script>
@endonce-->

<div id="inpost-widget-wrapper" style="display:none" class="mt-4 p-4 border rounded bg-white">
    <h3 class="font-semibold mb-2">📦 Wybierz paczkomat</h3>
 
    <div id="inpost-selected" class="{{ $savedPointId ? '' : 'hidden' }}">
        <p><b id="inpost-point-name">{{ $savedPointId }}</b></p>
        <p id="inpost-point-address">{{ $savedPointAddress }}</p>
        <button type="button" onclick="inpostOpenWidget()" class="mt-2 text-blue-600 underline">
            Zmień paczkomat
        </button>
    </div>
 
    <button
        id="inpost-open-btn"
        type="button"
        onclick="inpostOpenWidget()"
        class="{{ $savedPointId ? 'hidden' : '' }} mt-2 px-4 py-2 bg-yellow-400 rounded"
    >
        Wybierz paczkomat
    </button>
</div>
 
<div id="inpost-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;width:90%;max-width:900px;height:90vh;border-radius:8px;display:flex;flex-direction:column;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid #eee;">
            <span style="font-weight:600;">📦 Wybierz paczkomat InPost</span>
            <button type="button" onclick="inpostCloseWidget()" style="font-size:24px;line-height:1;background:none;border:none;cursor:pointer;">X</button>
        </div>
        <iframe
            id="inpost-iframe"
            src="{{ $iframeUrl }}"
            style="flex:1;width:100%;border:none;"
            allow="geolocation"
        ></iframe>
    </div>
</div>