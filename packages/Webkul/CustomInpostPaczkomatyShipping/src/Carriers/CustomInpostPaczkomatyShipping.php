<?php

namespace Webkul\CustomInpostPaczkomatyShipping\Carriers;

use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Checkout\Facades\Cart;

class CustomInpostPaczkomatyShipping extends AbstractShipping
{
    /**
     * Shipping method code - must match carriers.php key.
     */
    protected $code = 'custom_inpostpaczkomaty_shipping';

    /**
     * Calculate shipping rate for the current cart.
     */
    public function calculate()
    {
        // check if shipping method is available
        if (! $this->isAvailable()) {
            return false;
        }

        $cart = Cart::getCart();
        
        // create shipping rate object
        $object = new CartShippingRate;
        $object->carrier = 'custom_inpostpaczkomaty_shipping';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'custom_inpostpaczkomaty_shipping_custom_inpostpaczkomaty_shipping';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        
        // calculate rate - start with base rate
        $baseRate = $this->getConfigData('default_rate');
        $finalRate = $baseRate;
        
        // express shipping logic - you can customize this
        if ($this->getConfigData('type') === 'per_unit') {
            // calculate per item
            $totalItems = 0;

            foreach ($cart->items as $item) {
                if ($item->product->getTypeInstance()->isStockable()) {
                    $totalItems += $item->quantity;
                }
            }

            $finalRate = $baseRate * $totalItems;
        } else {
            // per order pricing (flat rate)
            $finalRate = $baseRate;
        }
        
        // set calculated prices
        $object->price = core()->convertPrice($finalRate);
        $object->base_price = $finalRate;

        return $object;
    }
}