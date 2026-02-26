@pushOnce('scripts')
    <script>console.log('InPost SDK: Próba ładowania z geowidget.inpost.pl...');</script>
    
    <link rel="stylesheet" href="https://geowidget.inpost.pl/v1/assets/css/geowidget.css">
    <script src="https://geowidget.inpost.pl/v1/assets/js/geowidget.js"></script>

    <script type="text/x-template" id="v-inpost-widget-template">
        <div v-if="visible" class="mt-6 p-4 border rounded-xl bg-gray-50 shadow-sm w-full">
            <h3 class="mb-4 font-bold text-navyBlue text-lg">Wybierz swój Paczkomat:</h3>
            
            <div v-if="sdkError" class="p-3 mb-3 bg-red-100 text-red-700 rounded-lg text-sm">
                Błąd: Nie udało się załadować mapy InPost. Sprawdź połączenie internetowe lub AdBlocka.
            </div>

            <div 
                ref="inpostMap" 
                class="w-full border border-gray-300 rounded-lg overflow-hidden bg-white"
                style="height: 400px;"
            ></div>

            <div v-if="selectedLocker" class="mt-4 p-3 bg-green-600 text-white rounded-lg flex justify-between items-center">
                <span>Wybrano: <strong>@{{ selectedLocker }}</strong></span>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">ZAPISANO</span>
            </div>
        </div>
    </script>

    <script type="module">
        console.log('InPost Component: Rejestracja...');

        app.component('v-inpost-widget', {
            template: '#v-inpost-widget-template',
            props: ['method'],
            data() {
                return {
                    selectedLocker: null,
                    widgetInstance: null,
                    visible: false,
                    sdkError: false
                }
            },
            watch: {
                method: {
                    immediate: true,
                    handler(newVal) {
                        this.visible = newVal && newVal.includes('inpost');
                        if (this.visible) {
                            this.$nextTick(() => this.initWidget());
                        }
                    }
                }
            },
            methods: {
                initWidget() {
                    if (this.widgetInstance) return;

                    // Sprawdzamy czy biblioteka InPostGeowidget fizycznie istnieje w oknie przeglądarki
                    if (typeof InPostGeowidget === 'undefined') {
                        console.error('InPost SDK: Biblioteka InPostGeowidget nie została załadowana!');
                        this.sdkError = true;
                        return;
                    }

                    const config = {
                        token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
                        language: "pl",
                        config: "parcelcollect"
                    };

                    console.log('InPost SDK: Inicjalizacja mapy...');

                    this.widgetInstance = new InPostGeowidget(config, (station) => {
                        this.selectedLocker = station.name + ", " + station.address.line1;
                        this.$axios.post("{{ route('inpost.save_paczkomat') }}", {
                            paczkomat_id: station.name,
                            paczkomat_details: this.selectedLocker
                        }).then(() => console.log('InPost: Zapisano.'));
                    });

                    this.widgetInstance.render(this.$refs.inpostMap);
                }
            }
        });
    </script>
@endPushOnce