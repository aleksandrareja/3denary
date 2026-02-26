@pushOnce('scripts')
    <link rel="stylesheet" href="https://sdk.inpost.pl/geowidget/v1/assets/css/geowidget.css">
    <script src="https://sdk.inpost.pl/geowidget/v1/assets/js/geowidget.js" defer></script>

    <script type="text/x-template" id="v-inpost-widget-template">
        <div v-if="visible" class="mt-6 p-5 border rounded-xl bg-gray-50 w-full shadow-sm">
            <h3 class="mb-4 font-bold text-navyBlue text-lg">Wybierz swój Paczkomat:</h3>
            
            <div 
                ref="geowidgetContainer" 
                class="w-full border border-gray-300 rounded-lg overflow-hidden bg-white"
                style="height: 450px;"
            ></div>

            <div v-if="selectedLocker" class="mt-4 p-4 bg-green-600 text-white rounded-lg font-medium flex justify-between items-center animate-pulse">
                <span>Wybrano punkt: <strong>@{{ selectedLocker }}</strong></span>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">Zapisano w koszyku</span>
            </div>
            
            <div v-else class="mt-4 p-4 bg-blue-50 text-blue-800 rounded-lg text-sm border border-blue-100 flex items-center gap-2">
                <span class="icon-information text-xl"></span>
                <span>Proszę kliknąć wybrany Paczkomat na mapie, aby zatwierdzić lokalizację.</span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-inpost-widget', {
            template: '#v-inpost-widget-template',

            props: ['method'], // Przyjmuje wybraną metodę z shipping.blade.php

            data() {
                return {
                    selectedLocker: null,
                    widgetInstance: null,
                    visible: false
                }
            },

            watch: {
                // Śledzimy zmianę metody wysyłki w locie
                method: {
                    immediate: true,
                    handler(newVal) {
                        // Sprawdzamy, czy wybrana metoda to Twój InPost
                        // Jeśli Twoja metoda nazywa się inaczej, popraw frazę poniżej
                        this.visible = newVal && newVal.includes('custom_inpostpaczkomaty_shipping');

                        if (this.visible) {
                            // Czekamy na wyrenderowanie kontenera przez Vue
                            this.$nextTick(() => {
                                this.initWidget();
                            });
                        }
                    }
                }
            },

            methods: {
                initWidget() {
                    // Blokada ponownej inicjalizacji
                    if (this.widgetInstance || !this.$refs.geowidgetContainer) return;

                    const config = {
                        token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
                        language: "pl",
                        config: "parcelcollect"
                    };

                    this.widgetInstance = new InPostGeowidget(config, (station) => {
                        this.selectedLocker = `${station.name}, ${station.address.line1}`;
                        
                        // AJAX: Zapisujemy kod paczkomatu do tabeli 'addresses'
                        this.$axios.post("{{ route('inpost.save_paczkomat') }}", {
                            paczkomat_id: station.name,
                            paczkomat_details: this.selectedLocker
                        })
                        .then(response => {
                            console.log("InPost: Wybór zapisany pomyślnie.");
                        })
                        .catch(error => {
                            console.error("InPost: Błąd podczas zapisu:", error);
                        });
                    });

                    this.widgetInstance.render(this.$refs.geowidgetContainer);
                }
            }
        });
    </script>
@endPushOnce