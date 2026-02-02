{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp

<footer class="mt-9 bg-navyBlue max-sm:mt-10 px-3">
    <div class="container flex max-1180:flex-wrap justify-between items-start gap-x-10 gap-y-8 p-16 max-md:gap-y-15 max-md:p-10 max-md:px-2">

        <!-- Logo -->
        <div class="logo max-md:mb-6 flex justify-center max-1180:w-full">
            <a href="{{ route('shop.home.index') }}">
                <img  
                    src="{{ asset('storage/channel/'. core()->getCurrentChannel()->id . '/logo3_biale.png') }}" 
                    class="h-20 w-auto max-md:h-16 max-sm:h-14"
                    alt="{{ config('app.name') }}"
                />
            </a>
        </div>

        <!-- Footer links -->
        <div class="flex items-start gap-10 max-1180:gap-6 justify-between max-sm:gap-30 max-1180:w-full">
            @if ($customization?->options)
                @foreach ($customization->options as $footerLinkSection)
                    <ul class="grid gap-3 text-sm text-left">
                        @php
                            usort($footerLinkSection, fn($a, $b) => $a['sort_order'] - $b['sort_order']);
                        @endphp

                        @foreach ($footerLinkSection as $link)
                            <li class="first:font-bold first:text-lightOrange text-transparentOrange hover:text-lightOrange transition">
                                <a href="{{ $link['url'] }}">
                                    {{ $link['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            @endif
        </div>

        <!-- Newsletter subscription -->
        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}
        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
            <div class="grid gap-2.5 max-sm:mt-6 max-1180:w-full">
                <p class="max-w-[400px] text-2xl text-lightOrange font-semibold max-1180:w-full">
                    @lang('shop::app.components.layouts.footer.newsletter-text')
                </p>

                <p class="mt-4 text-lg text-lightOrange font-semibold">
                    @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                </p>

                <div class="mt-2.5 flex justify-center max-md:justify-start">
                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        class="w-full max-w-md"
                    >
                        <div class="flex w-full justify-between gap-4">
                                <x-shop::form.control-group.control
                                    type="email"
                                    class="bg-transparent border-0 placeholder:text-transparentOrange text-white"
                                    name="email"
                                    rules="required|email"
                                    label="Email"
                                    :aria-label="trans('shop::app.components.layouts.footer.email')"
                                    placeholder="email@example.com"
                                />

                            <button
                                type="submit"
                                class="flex h-max-[50px] font-semibold text-lightOrange items-center whitespace-nowrap rounded-md bg-goldenOrange px-7 py-2.5 font-medium hover:bg-darkGreen transition max-md:px-5 max-md:text-xs max-sm:px-4 max-sm:py-2 max-sm:rounded-lg"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form>
                </div>
            </div>
        @endif
        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
    </div>

    <!-- Footer bottom -->
    <div class="flex border-t border-transparentOrange m-5 px-16 py-3.5 justify-center max-sm:px-5">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}
        <p class="text-sm text-transparentOrange text-center">
            @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
        </p>
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
