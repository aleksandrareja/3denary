<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang("admin::app.errors.{$errorCode}.title")
    </x-slot>

    <div class="flex min-h-screen flex-col items-center justify-center px-6 text-center">

        <div class="relative mb-6">
            <div class="text-[160px] font-bold text-zinc-200 select-none max-md:text-[100px]">
                {{ $errorCode }}
            </div>

            <div class="absolute inset-0 text-[160px] font-bold text-navyBlue opacity-10 blur-sm max-md:text-[100px]">
                {{ $errorCode }}
            </div>
        </div>

        <h1 class="text-3xl font-semibold text-gray-800 max-md:text-xl">
            @lang("admin::app.errors.{$errorCode}.title")
        </h1>

        <p class="mt-4 max-w-md text-base text-zinc-500 max-md:text-sm">
            {{ 
                $errorCode === 503 && core()->getCurrentChannel()->maintenance_mode_text != ""
                ? core()->getCurrentChannel()->maintenance_mode_text 
                : trans("admin::app.errors.{$errorCode}.description")
            }}
        </p>

        <a 
            href="{{ route('shop.home.index') }}"
            class="mt-8 inline-block rounded-xl px-8 py-3 text-base font-medium text-black transition hover:scale-[0.97]"
            style="background-color:#FFD700;"
        >
            @lang('shop::app.errors.go-to-home')
        </a>

    </div>
</x-shop::layouts>