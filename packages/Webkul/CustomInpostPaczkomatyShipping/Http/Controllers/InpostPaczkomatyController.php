<?php

namespace Webkul\CustomInpostPaczkomatyShipping\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Illuminate\Routing\Controller;

class InpostPaczkomatyController extends Controller
{
    public function savePaczkomat(Request $request)
    {
        $cart = Cart::getCart();

        if ($cart && $cart->shipping_address) {
            // Aktualizujemy rekord w tabeli 'addresses'
            $cart->shipping_address->update([
                'paczkomat_id'      => $request->paczkomat_id,
                'paczkomat_details' => $request->paczkomat_details
            ]);

            return response()->json([
                'status' => true, 
                'message' => 'Paczkomat zapisany'
            ]);
        }

        return response()->json(['status' => false], 400);
    }
}