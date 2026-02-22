<?php

namespace Webkul\CustomPrzelewy24Payment\Payment;

use Webkul\Payment\Payment\Payment;

class CustomPrzelewy24Payment extends Payment
{
    /**
     * Payment method code - must match payment-methods.php key.
     */
    protected $code = 'custom_przelewy24_payment';

    /**
     * Get redirect URL for payment processing.
     * 
     * Note: You need to create this route in your Routes/web.php file
     * or return null if you don't need a redirect.
     */
    public function getRedirectUrl()
    {
        // return route('custom_przelewy24_payment.process');
        return null; // No redirect needed for this basic example
    }

    /**
     * Get additional details for frontend display.
     */
    public function getAdditionalDetails()
    {
        return [
            'title' => $this->getConfigData('title'),
            'description' => $this->getConfigData('description'),
            'requires_card_details' => true,
        ];
    }

    /**
     * Get payment method configuration data.
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.custom_przelewy24_payment.' . $field);
    }
}