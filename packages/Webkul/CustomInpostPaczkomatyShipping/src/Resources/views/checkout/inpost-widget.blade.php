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
    document.addEventListener("DOMContentLoaded", function() {
        let widgetInstance = null;

        // Funkcja sprawdzająca czy wybrano InPost
        function checkShippingMethod() {
            // Szukamy zaznaczonego inputa wysyłki
            const selectedMethod = document.querySelector('input[name="shipping_method"]:checked');
            const container = document.getElementById('inpost-map-container');
            
            console.log('Selected method value:', selectedMethod ? selectedMethod.value : 'none');
            console.log('Looking for: custom_inpostpaczkomaty_shipping_custom_inpostpaczkomaty_shipping');
            
            // Wartość musi być zgodna z tym, co zwraca metoda calculate() w Carrierze
            if (selectedMethod && selectedMethod.value === "custom_inpostpaczkomaty_shipping_custom_inpostpaczkomaty_shipping") {
                console.log('InPost method selected - showing widget');
                container.style.display = 'block';
                
                // Inicjalizujemy widget tylko raz
                if (!widgetInstance) {
                    initInPostWidget();
                }
            } else {
                console.log('Other method selected - hiding widget');
                if (container) container.style.display = 'none';
            }
        }

        function initInPostWidget() {
            const config = {
                // Pobiera token GEO API z Twojej konfiguracji w adminie
                token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
                language: "pl",
                config: "parcelcollect"
            };

            widgetInstance = new InPostGeowidget(config, (station) => {
                const paczkomatId = station.name;
                const details = `${station.name}, ${station.address.line1}, ${station.address.line2}`;

                // Wyświetlamy informację pod mapą
                const infoBox = document.getElementById('selected-paczkomat-name');
                infoBox.innerText = "Wybrano: " + details;
                infoBox.style.display = 'block';

                // AJAX: Wysyłamy dane do Twojego kontrolera, aby zapisać je w tabeli 'addresses'
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
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Status zapisu InPost:", data.message);
                })
                .catch(error => console.error('Błąd zapisu paczkomatu:', error));
            });

            widgetInstance.render(document.getElementById('geowidget'));
        }

        // Ponieważ Bagisto to SPA (Vue.js), musimy nasłuchiwać zmian w dokumentach
        // Event 'change' na body wyłapie zmianę wyboru metody wysyłki
        document.body.addEventListener('change', function(e) {
            if (e.target.name === 'shipping_method') {
                checkShippingMethod();
            }
        });

        // Uruchamiamy sprawdzenie na starcie (np. przy odświeżeniu strony)
        setTimeout(checkShippingMethod, 2000);
    });
</script>