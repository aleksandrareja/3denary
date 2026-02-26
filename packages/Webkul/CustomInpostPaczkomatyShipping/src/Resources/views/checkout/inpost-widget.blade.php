@pushOnce('scripts')
    <script>console.log('InPost SDK: Rozpoczęto ładowanie skryptów...');</script>
    
    <link rel="stylesheet" href="https://sdk.inpost.pl/geowidget/v1/assets/css/geowidget.css">
    <script src="https://sdk.inpost.pl/geowidget/v1/assets/js/geowidget.js"></script>

    <script type="text/x-template" id="v-inpost-widget-template">
        <div v-if="visible" class="mt-6 p-4 border rounded-xl bg-gray-50 shadow-sm w-full">
            <h3 class="mb-4 font-bold text-navyBlue text-lg">Wybierz swój Paczkomat:</h3>
            
            <div 
                ref="inpostMap" 
                class="w-full border border-gray-300 rounded-lg overflow-hidden bg-white"
                style="height: 400px;"
            ></div>

            <div v-if="selectedLocker" class="mt-4 p-3 bg-green-600 text-white rounded-lg flex justify-between items-center">
                <span>Wybrano: <strong>@{{ selectedLocker }}</strong></span>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">LOG: ZAPISYWANIE...</span>
            </div>
        </div>
    </script>

    <script type="module">
        console.log('InPost Component: Rejestracja komponentu v-inpost-widget...');

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
                        console.log('InPost Component: Zmiana metody na:', newVal);
                        
                        this.visible = newVal && newVal.includes('inpost');
                        
                        if (this.visible) {
                            console.log('InPost Component: Metoda InPost wykryta, inicjuję mapę...');
                            this.$nextTick(() => this.initWidget());
                        } else {
                            console.log('InPost Component: Wybrano inną metodę, mapa ukryta.');
                        }
                    }
                }
            },

            methods: {
                initWidget() {
                    if (this.widgetInstance) {
                        console.log('InPost Component: Mapa już zainicjowana, pomijam.');
                        return;
                    }

                    const config = {
                        token: "{{ core()->getConfigData('sales.carriers.custom_inpostpaczkomaty_shipping.geo_api_key') }}",
                        language: "pl",
                        config: "parcelcollect"
                    };

                    console.log('InPost Component: Renderuję InPostGeowidget z tokenem:', config.token);

                    try {
                        this.widgetInstance = new InPostGeowidget(config, (station) => {
                            console.log('InPost Component: Wybrano stację:', station.name);
                            
                            this.selectedLocker = station.name + ", " + station.address.line1;
                            
                            this.$axios.post("{{ route('inpost.save_paczkomat') }}", {
                                paczkomat_id: station.name,
                                paczkomat_details: this.selectedLocker
                            })
                            .then(res => console.log('InPost AJAX: Pomyślnie zapisano w bazie.'))
                            .catch(err => console.error('InPost AJAX: Błąd zapisu!', err));
                        });

                        this.widgetInstance.render(this.$refs.inpostMap);
                    } catch (e) {
                        console.error('InPost Component: Błąd krytyczny SDK InPost:', e);
                    }
                }
            }
        });
    </script>
@endPushOnce