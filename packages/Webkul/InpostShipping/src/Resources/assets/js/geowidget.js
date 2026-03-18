(function () {
    var METHOD_CODE = 'inpost_inpost';

    function showWidget() {
        var el = document.getElementById('inpost-widget-wrapper');
        if (el) el.style.display = 'block';
    }

    function hideWidget() {
        var el = document.getElementById('inpost-widget-wrapper');
        if (el) el.style.display = 'none';
    }

    window.inpostOpenWidget = function () {
        var modal = document.getElementById('inpost-modal');
        if (modal) modal.style.display = 'flex';

        var container = document.getElementById('inpost-map');
        if (container && !container.hasChildNodes()) {
            var widget = document.createElement('inpost-geowidget');
            widget.setAttribute('token', window.INPOST_TOKEN || '');
            widget.setAttribute('language', 'pl');
            widget.setAttribute('config', 'parcelcollect');
            widget.setAttribute('onpoint', 'window.onInpostSelect');
            widget.style.cssText = 'width:100%;height:100%;display:block;';
            container.appendChild(widget);
        }
    };

    window.inpostCloseWidget = function () {
        var modal = document.getElementById('inpost-modal');
        if (modal) modal.style.display = 'none';
    };

    window.onInpostSelect = function (point) {
        inpostCloseWidget();

        var pointId = point.name;
        var addr = point.address_details || {};
        var pointAddress = addr.street
            ? (addr.street + ' ' + (addr.building_number || '') + ', ' + (addr.post_code || '') + ' ' + (addr.city || '')).trim()
            : ((point.address && point.address.line1) || '');

        var nameEl = document.getElementById('inpost-point-name');
        var addrEl = document.getElementById('inpost-point-address');
        var selEl  = document.getElementById('inpost-selected');
        var btnEl  = document.getElementById('inpost-open-btn');

        if (nameEl) nameEl.textContent = pointId;
        if (addrEl) addrEl.textContent = pointAddress;
        if (selEl)  selEl.classList.remove('hidden');
        if (btnEl)  btnEl.classList.add('hidden');

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        fetch(window.INPOST_SAVE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : (window.INPOST_CSRF || ''),
            },
            body: JSON.stringify({
                point_id:      pointId,
                point_name:    pointId,
                point_address: pointAddress,
            }),
        })
        .then(function (r) { return r.json(); })
        .catch(function (e) { console.error('InPost save error:', e); });
    };

    document.addEventListener('change', function (e) {
        if (!e.target || e.target.name !== 'shipping_method') return;
        if (e.target.value === METHOD_CODE) {
            showWidget();
        } else {
            hideWidget();
        }
    });

    function checkInitial() {
        var checked = document.querySelector('input[name="shipping_method"]:checked');
        if (checked && checked.value === METHOD_CODE) showWidget();
    }

    var obs = new MutationObserver(function () { checkInitial(); });
    obs.observe(document.body, { childList: true, subtree: true });
    setTimeout(function () { obs.disconnect(); }, 15000);
    checkInitial();
})();