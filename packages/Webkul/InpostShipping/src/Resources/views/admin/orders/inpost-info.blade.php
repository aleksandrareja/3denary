{{-- InPost locker info — injected into the admin order view --}}

@if (isset($order) && $order->shipping_method === 'inpost_inpost' && $order->inpost_point_id)
    <div class="mt-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4">
        <h3 class="mb-2 flex items-center gap-2 text-base font-semibold text-yellow-900">
            <span class="text-xl">📦</span>
            {{ __('inpost::app.admin.inpost-locker') }}
        </h3>

        <dl class="space-y-1 text-sm text-yellow-800">
            <div class="flex gap-2">
                <dt class="font-medium w-24 shrink-0">
                    {{ __('inpost::app.admin.locker-id') }}:
                </dt>
                <dd>{{ $order->inpost_point_id }}</dd>
            </div>

            <div class="flex gap-2">
                <dt class="font-medium w-24 shrink-0">
                    {{ __('inpost::app.admin.locker-address') }}:
                </dt>
                <dd>{{ $order->inpost_point_address }}</dd>
            </div>
        </dl>

        <a
            href="https://inpost.pl/znajdz-paczkomat?name={{ urlencode($order->inpost_point_id) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="mt-2 inline-flex items-center gap-1 text-xs text-blue-600 underline hover:text-blue-800"
        >
            {{ __('inpost::app.admin.view-on-map') }} ↗
        </a>
    </div>
@endif
