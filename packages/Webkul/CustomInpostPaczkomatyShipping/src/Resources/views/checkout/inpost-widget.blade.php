@pushOnce('scripts')
    <link rel="stylesheet" href="https://geowidget.inpost.pl/v1/assets/css/geowidget.css">
    <script src="https://geowidget.inpost.pl/v1/assets/js/geowidget.js"></script>

    <script type="text/x-template" id="v-inpost-widget-template">
        <div v-show="visible" class="mt-6 p-4 border rounded-xl bg-white shadow-sm w-full" style="min-height: 500px; display: block; clear: both;">
            <h3 class="mb-4 font-bold text-navyBlue text-lg">Wybierz swój Paczkomat:</h3>
            
            <div 
                id="geowidget"
                ref="inpostMap" 
                style="width: 100%; height: 450px; position: relative; border: 1px solid #eee;"
            ></div>

            <div v-if="selectedLocker" class="mt-4 p-3 bg-green-600 text-white rounded-lg flex justify-between items-center">
                <span>Wybrano: <strong>@{{ selectedLocker }}</strong></span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-inpost-widget', {
            template: '#v-inpost-widget-template',
            props: ['method'],
            data() {
                return {
                    selectedLocker: null,
                    widgetInstance: null,
                    visible: false
                }
            },
            watch: {
                method: {
                    immediate: true,
                    handler(newVal) {
                        this.visible = newVal && newVal.includes('inpost');
                        console.log('Widget: Czy widoczny?', this.visible, 'Metoda:', newVal);
                        
                        if (this.visible) {
                            this.$nextTick(() => {
                                // Małe opóźnienie, żeby Vue na pewno wyrenderowało kontener
                                setTimeout(() => this.initWidget(), 200);
                            });
                        }
                    }
                }
            },
            methods: {
                initWidget() {
                    if (this.widgetInstance) {
                        console.log('Widget: Mapa już istnieje. Jeśli jej nie widzisz, sprawdź CSS.');
                        return;
                    }

                    const token = "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}";
                    console.log('Widget: Start renderowania z tokenem:', token ? 'OK' : 'BRAK!');

                    const config = {
                        token: token,
                        language: "pl",
                        config: "parcelcollect"
                    };

                    try {
                        this.widgetInstance = new InPostGeowidget(config, (station) => {
                            this.selectedLocker = station.name + ", " + station.address.line1;
                            this.$axios.post("{{ route('inpost.save_paczkomat') }}", {
                                paczkomat_id: station.name,
                                paczkomat_details: this.selectedLocker
                            });
                        });

                        // Renderujemy do elementu przez ref
                        this.widgetInstance.render(this.$refs.inpostMap);
                        console.log('Widget: Wywołano funkcję render(). Sprawdź okno mapy.');
                    } catch (e) {
                        console.error('Widget: Błąd SDK:', e);
                    }
                }
            }
        });
    </script>
@endpushOnce