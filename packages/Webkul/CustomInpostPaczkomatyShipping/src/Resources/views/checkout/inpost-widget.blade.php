<link rel="stylesheet" href="https://sdk.inpost.pl/geowidget/v1/assets/css/geowidget.css">
<script src="https://sdk.inpost.pl/geowidget/v1/assets/js/geowidget.js" defer></script>

<script>
console.log('InPost widget blade loaded');
</script>

<style>
    #inpost-map-container {
        width: 100%;
        margin-top: 15px;
        border: 1px solid #e2e2e2;
        padding: 10px;
        background: #fdfdfd;
        display: none; /* Ukryty domyślnie */
    }
    #geowidget {
        width: 100%;
        height: 400px;
    }
    .selected-paczkomat-info {
        background: #27ae60;
        color: white;
        padding: 10px;
        margin-top: 10px;
        font-weight: bold;
        border-radius: 3px;
    }
</style>

<div id="inpost-map-container">
    <p><strong>Wybierz Paczkomat z mapy:</strong></p>
    <div id="geowidget"></div>
    <div id="selected-paczkomat-name" class="selected-paczkomat-info" style="display: none;"></div>
</div>

<script>
    let widgetInstance = null;

    function checkShippingMethod() {
        const selectedMethod = document.querySelector('input[name="shipping_method"]:checked');
        const container = document.getElementById('inpost-map-container');

        if (!container) return;

        if (selectedMethod && selectedMethod.value === "custom_inpostpaczkomaty_shipping_custom_inpostpaczkomaty_shipping") {
            container.style.display = 'block';

            if (!widgetInstance) {
                setTimeout(() => {
                    initInPostWidget();
                }, 100);
            }
        } else {
            container.style.display = 'none';
        }
    }

    function initInPostWidget() {
        if (!document.getElementById('geowidget')) return;

        const config = {
            token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
            language: "pl",
            config: "parcelcollect"
        };

        widgetInstance = new InPostGeowidget(config, (station) => {
            const paczkomatId = station.name;
            const details = `${station.name}, ${station.address.line1}, ${station.address.line2}`;

            const infoBox = document.getElementById('selected-paczkomat-name');
            infoBox.innerText = "Wybrano: " + details;
            infoBox.style.display = 'block';

            fetch("{{ route('inpost.save_paczkomat') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    paczkomat_id: paczkomatId,
                    paczkomat_details: details
                })
            });
        });

        widgetInstance.render(document.getElementById('geowidget'));
    }

    // Nasłuchiwanie zmian (ważne w SPA)
    document.addEventListener('change', function(e) {
        if (e.target.name === 'shipping_method') {
            setTimeout(checkShippingMethod, 100);
        }
    });
</script>
