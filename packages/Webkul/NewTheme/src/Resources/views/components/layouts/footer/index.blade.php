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

<footer class="mt-9 bg-navyBlue px-3">
    <div class="container mx-auto grid max-md:grid-cols-1 grid-cols-2 gap-12 p-16 max-md:p-10 max-sm:px-4 items-center justify-center">

        <!-- LEFT COLUMN -->
        <div class="flex flex-col gap-6 max-w-md">

            <!-- Logo -->
            <a href="{{ route('shop.home.index') }}" class="w-fit">
                <img  
                    src="{{ asset('storage/channel/'. core()->getCurrentChannel()->id . '/logo3_biale.png') }}" 
                    class="h-20 w-auto max-md:h-16"
                    alt="{{ config('app.name') }}"
                />
            </a>

            <!-- Description -->
            <p class="text-transparentOrange text-sm">
                3Denary to sklep z pasją do numizmatyki. Oferujemy polskie i zagraniczne
                monety kolekcjonerskie – historyczne i współczesne, złote, srebrne i obiegowe.
            </p>

            <!-- Facebook -->
            <a
                href="https://www.facebook.com/"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center gap-2 text-lightOrange text-sm hover:text-goldenOrange transition w-fit"
            >
                <!-- możesz podmienić na ikonę SVG -->
                <span class="font-semibold">Facebook</span>
                <span class="text-xs text-transparentOrange">/ 3Denary</span>
            </a>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="flex gap-12 items-start lg:justify-end max-md:gap-5">

            @if ($customization?->options)
                @foreach ($customization->options as $footerLinkSection)
                    <ul class="grid gap-3 text-sm md:min-w-[140px] max-md:gap-2">
                        @php
                            usort($footerLinkSection, fn($a, $b) => $a['sort_order'] - $b['sort_order']);
                        @endphp

                        @foreach ($footerLinkSection as $link)
                            <li class="first:font-semibold first:text-lightOrange text-transparentOrange hover:text-lightOrange transition text-wrap">
                                <a href="{{ $link['url'] }}">
                                    {{ $link['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            @endif

        </div>
    </div>

    <!-- Footer bottom -->
    <div class="border-t border-transparentOrange/40 px-6 py-4 text-center">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}
        <p class="text-sm text-transparentOrange">
            @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
        </p>
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>


{!! view_render_event('bagisto.shop.layout.footer.after') !!}
