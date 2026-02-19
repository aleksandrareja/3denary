@php
    $customer = auth()->guard('customer')->user();
@endphp

<div class="panel-side journal-scroll grid max-h-[1320px] min-w-[342px] max-w-[380px] grid-cols-[1fr] gap-8 overflow-y-auto overflow-x-hidden max-xl:min-w-[270px] max-md:max-w-full max-md:gap-5">
    <!-- Account Profile Hero Section -->
    <div class="grid grid-cols-[auto_1fr] items-center gap-4 border-b border-zinc-200 py-[25px] max-md:py-2.5">
        <div class="">
            <img
                src="{{ $customer->image_url ??  bagisto_asset('images/user-placeholder.png') }}"
                class="h-[60px] w-[60px] rounded-full"
                alt="Profile Image"
            >
        </div>

        <div class="flex flex-col justify-between">
            <p class="font-mediums break-all text-2xl max-md:text-xl">Witaj {{ $customer->first_name }}</p>

            <p class="max-md:text-md: text-zinc-500 no-underline">{{ $customer->email }}</p>
        </div>
    </div>

    <!-- Account Navigation Menus -->
@foreach (menu()->getItems('customer') as $menuItem)
    <div class="mb-10">

        <!-- Section Title -->
        <div class="pb-4">
            <p class="text-xl font-semibold tracking-wide text-gray-800 max-md:text-lg">
                {{ $menuItem->getName() }}
            </p>
            <div class="w-10 h-[2px] bg-goldenOrange mt-2"></div>
        </div>

        @if ($menuItem->haveChildren())
            <div class="flex flex-col space-y-2">

                @foreach ($menuItem->getChildren() as $subMenuItem)
                    <a href="{{ $subMenuItem->getUrl() }}"
                       class="group relative py-3 block">

                        <div class="flex justify-between items-center transition-all duration-300">

                            <p class="flex items-center gap-x-4 text-base font-medium
                                transition-all duration-300
                                {{ $subMenuItem->isActive() ? 'text-goldenOrange' : 'text-gray-700' }}
                                group-hover:text-goldenOrange">

                                <span class="{{ $subMenuItem->getIcon() }}
                                    text-xl transition-all duration-300
                                    group-hover:text-goldenOrange">
                                </span>

                                {{ $subMenuItem->getName() }}
                            </p>

                            <span class="icon-arrow-right rtl:icon-arrow-left
                                text-lg text-gray-400
                                transition-all duration-300
                                group-hover:text-goldenOrange
                                group-hover:translate-x-1">
                            </span>

                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endforeach


</div>