@component('shop::emails.layout')
    <div style="max-width: 600px; margin: 40px auto; font-family: 'Helvetica', Arial, sans-serif; background-color: #fff8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 40px;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="font-size: 28px; color: #121A26; margin: 0; font-weight: 700;">
                @lang('shop::app.emails.dear', ['customer_name' => $customer->name]) 👋
            </h1>
            <p style="font-size: 16px; color: #384860; margin-top: 8px;">
                @lang('shop::app.emails.customers.verification.greeting')
            </p>
        </div>

        <div style="font-size: 16px; color: #384860; line-height: 1.6; margin-bottom: 40px;">
            <p>
                @lang('shop::app.emails.customers.verification.description')
            </p>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <a
                href="{{ route('shop.customers.verify', $customer->token) }}"
                style="display: inline-block; padding: 16px 50px; font-size: 16px; font-weight: 700; color: #ffffff; background-color: #38200F; text-decoration: none; border-radius: 8px; text-transform: uppercase; transition: background 0.3s;"
                onmouseover="this.style.backgroundColor='#52321a'"
                onmouseout="this.style.backgroundColor='#38200F'"
            >
                @lang('shop::app.emails.customers.verification.verify-email')
            </a>
        </div>

        <div style="font-size: 14px; color: #9ca3af; text-align: center;">
            <p style="margin: 0;">@lang('shop::app.emails.customers.verification.footer')</p>
        </div>
    </div>
@endcomponent