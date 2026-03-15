@component('shop::emails.layout')

<div style="background:#ffffff;padding:35px 30px;border-top:1px solid #e4e4e7;border-bottom:1px solid #e4e4e7; text-align:center;">

    <p style="font-weight:600;font-size:22px;color:#121A26;line-height:28px;margin-bottom:20px;">
        @lang('shop::app.emails.dear', ['customer_name' => $customer->name]), 👋
    </p>

    <p style="font-size:16px;color:#384860;line-height:26px;margin-bottom:18px;">
        @lang('shop::app.emails.customers.verification.greeting')
    </p>

    <p style="font-size:16px;color:#384860;line-height:26px;margin-bottom:35px;">
        @lang('shop::app.emails.customers.verification.description')
    </p>

    <div style="text-align:center;margin:35px 0;">
        <a
            href="{{ route('shop.customers.verify', $customer->token) }}"
            style="background:#38200F;color:#ffffff;padding:16px 40px;
                   text-decoration:none;font-weight:600;font-size:14px;
                   letter-spacing:0.5px;border-radius:6px;display:inline-block;
                   text-transform:uppercase;">
            @lang('shop::app.emails.customers.verification.verify-email')
        </a>
    </div>

    <p style="font-size:14px;color:#6b7280;line-height:22px;margin:0;">
        Jeśli nie tworzyłeś konta w naszym sklepie, możesz bezpiecznie zignorować tę wiadomość.
    </p>

</div>

@endcomponent