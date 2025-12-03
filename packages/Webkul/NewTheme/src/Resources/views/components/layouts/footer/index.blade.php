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

<footer class="mt-9 bg-navyBlue max-sm:mt-10">
    <div class="flex flex-wrap justify-between items-start gap-x-10 gap-y-8 p-16 max-lg:justify-center max-md:flex-col max-md:items-center max-md:gap-y-15 max-md:p-10 max-md:px-2">

        <!-- Logo -->
        <div class="logo max-md:mb-6 flex justify-center">
            <a href="{{ route('shop.home.index') }}">
                <img  
                    src="{{ asset('storage/channel/'. core()->getCurrentChannel()->id . '/logo3_biale.png') }}" 
                    class="h-20 w-auto max-md:h-16 max-sm:h-14"
                    alt="{{ config('app.name') }}"
                />
            </a>
        </div>

        <!-- Footer links -->
        <div class="flex flex-wrap items-start gap-24 max-1180:gap-6 justify-center max-sm:gap-30">
            @if ($customization?->options)
                @foreach ($customization->options as $footerLinkSection)
                    <ul class="grid gap-5 text-md max-1180:text-sm text-center max-md:text-left">
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
            <div class="grid gap-2.5 text-center max-md:text-left">
                <p class="max-w-[320px] text-3xl text-lightOrange max-md:text-2xl max-sm:text-lg mx-auto max-md:mx-0">
                    @lang('shop::app.components.layouts.footer.newsletter-text')
                </p>

                <p class="text-xs text-lightOrange">
                    @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                </p>

                <div class="mt-2.5 flex justify-center max-md:justify-start">
                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        class="w-full max-w-lg"
                    >
                        <div class="relative w-full">
                            <div class="absolute inset-0">
                                <x-shop::form.control-group.control
                                    type="email"
                                    class="block w-full rounded-xl border-2 border-[#e9decc] bg-zinc-100 px-5 py-4 text-sm max-md:p-3 max-sm:text-xs max-sm:py-2"
                                    name="email"
                                    rules="required|email"
                                    label="Email"
                                    :aria-label="trans('shop::app.components.layouts.footer.email')"
                                    placeholder="email@example.com"
                                />
                            </div>

                            <x-shop::form.control-group.error control-name="email" />

                            <button
                                type="submit"
                                class="absolute top-1/2 right-2 flex items-center whitespace-nowrap rounded-xl bg-lightOrange px-7 py-2.5 font-medium hover:bg-zinc-300 transition max-md:px-5 max-md:text-xs max-sm:px-4 max-sm:py-2 max-sm:rounded-lg"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                    </x-shop::form>
                </div>
            </div>
        @endif
        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
    </div>

    <!-- Footer bottom -->
    <div class="flex justify-between border-t border-transparentOrange m-5 px-16 py-3.5 max-md:justify-center max-sm:px-5">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}
        <p class="text-sm text-transparentOrange text-center">
            @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
        </p>
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
