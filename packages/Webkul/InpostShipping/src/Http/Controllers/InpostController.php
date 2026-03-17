<?php

namespace Webkul\InpostShipping\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;

class InpostController extends Controller
{
    /**
     * Save selected InPost locker point to session and cart shipping rate.
     */
    public function savePoint(Request $request): JsonResponse
    {
        $request->validate([
            'point_id'      => 'required|string|max:20',
            'point_name'    => 'required|string|max:20',
            'point_address' => 'required|string|max:255',
        ]);

        $pointId      = $request->input('point_id');
        $pointName    = $request->input('point_name');
        $pointAddress = $request->input('point_address');

        // Persist in session — used when placing the order
        session([
            'inpost_point_id'      => $pointId,
            'inpost_point_name'    => $pointName,
            'inpost_point_address' => $pointAddress,
        ]);

        // Also update the CartShippingRate row so it is available during order creation
        $cart = Cart::getCart();

        if ($cart) {
            CartShippingRate::where('cart_id', $cart->id)
                ->where('method', 'inpost_inpost')
                ->update([
                    'inpost_point_id'      => $pointId,
                    'inpost_point_name'    => $pointName,
                    'inpost_point_address' => $pointAddress,
                ]);
        }

        return response()->json([
            'success' => true,
            'point'   => [
                'id'      => $pointId,
                'name'    => $pointName,
                'address' => $pointAddress,
            ],
        ]);
    }
}
