{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar></v-toolbar>

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , 'Webkul\Product\Helpers\Toolbar')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id='v-toolbar-template'
    >
        <div>
            <!-- Desktop Toolbar -->
            <div class="flex items-center justify-end gap-8 max-md:hidden text-sm">

                {!! view_render_event('bagisto.shop.categories.toolbar.filter.before') !!}

                <!-- Sortowanie -->
                <x-shop::dropdown class="z-[1]" position="bottom-left">
                    <x-slot:toggle>
                        <button class="flex min-w-[180px] items-center justify-between gap-4 text-navyBlue">
                            @{{ sortLabel ?? "@lang('shop::app.products.sort-by.title')" }}
                            <span class="icon-arrow-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <x-slot:menu>
                        <x-shop::dropdown.menu.item
                            v-for="(sort, key) in filters.available.sort"
                            ::class="{'bg-gray-100': sort.value == filters.applied.sort}"
                            @click="apply('sort', sort.value)"
                        >
                            @{{ sort.title }}
                        </x-shop::dropdown.menu.item>
                    </x-slot>
                </x-shop::dropdown>

                {!! view_render_event('bagisto.shop.categories.toolbar.filter.after') !!}

                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.before') !!}

                <!-- Limit -->
                <x-shop::dropdown position="bottom-right">
                    <x-slot:toggle class="max-md:hidden">
                        <button class="flex items-center justify-between gap-4 text-navyBlue">
                            @{{ filters.applied.limit ?? "@lang('shop::app.categories.toolbar.show')" }}
                            <span class="icon-arrow-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <x-slot:menu>
                        <x-shop::dropdown.menu.item
                            v-for="(limit, key) in filters.available.limit"
                            ::class="{'bg-gray-100': limit == filters.applied.limit}"
                            @click="apply('limit', limit)"
                        >
                            @{{ limit }}
                        </x-shop::dropdown.menu.item>
                    </x-slot>
                </x-shop::dropdown>

                <!-- Grid / List -->
                <div class="flex items-center gap-4 max-md:hidden">
                    <span
                        class="cursor-pointer text-2xl text-navyBlue"
                        :class="filters.applied.mode === 'list' ? 'icon-listing-fill' : 'icon-listing'"
                        @click="changeMode('list')"
                    ></span>

                    <span
                        class="cursor-pointer text-2xl text-navyBlue"
                        :class="filters.applied.mode === 'grid' ? 'icon-grid-view-fill' : 'icon-grid-view'"
                        @click="changeMode('grid')"
                    ></span>
                </div>

                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.after') !!}
            </div>


            <!-- Mobile Toolbar -->
            <div class="md:hidden">
                <ul>
                    <li
                        class="px-4 py-2.5"
                        :class="{'bg-gray-100': sort.value == filters.applied.sort}"
                        v-for="(sort, key) in filters.available.sort"
                        @click="apply('sort', sort.value)"
                    >
                        @{{ sort.title }}
                    </li>
                </ul>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toolbar', {
            template: '#v-toolbar-template',

            data() {
                return {
                    filters: {
                        available: {
                            sort: @json($toolbar->getAvailableOrders()),

                            limit: @json($toolbar->getAvailableLimits()),

                            mode: @json($toolbar->getAvailableModes()),
                        },

                        default: {
                            sort: '{{ $toolbar->getOrder([])['value'] }}',

                            limit: '{{ $toolbar->getLimit([]) }}',

                            mode: '{{ $toolbar->getMode([]) }}',
                        },

                        applied: {
                            sort: '{{ $toolbar->getOrder($params ?? [])['value'] }}',

                            limit: '{{ $toolbar->getLimit($params ?? []) }}',

                            mode: '{{ $toolbar->getMode($params ?? []) }}',
                        }
                    }
                };
            },

            created() {
                let queryParams = new URLSearchParams(window.location.search);

                queryParams.forEach((value, filter) => {
                    if (['sort', 'limit', 'mode'].includes(filter)) {
                        this.filters.applied[filter] = value;
                    }
                });
            },

            mounted() {
                this.setFilters();
            },

            computed: {
                sortLabel() {
                    return this.filters.available.sort.find(sort => sort.value === this.filters.applied.sort).title;
                }
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.setFilters();
                },

                changeMode(value = 'grid') {
                    this.filters.applied['mode'] = value;

                    this.setFilters();
                },

                setFilters() {
                    let filters = {};

                    for (let key in this.filters.applied) {
                        if (this.filters.applied[key] != this.filters.default[key]) {
                            filters[key] = this.filters.applied[key];
                        }
                    }

                    this.$emitter.emit('toolbar-filter-applied', {
                        default: this.filters.default,
                        applied: filters,
                    });
                }
            },
        });
    </script>
@endPushOnce
