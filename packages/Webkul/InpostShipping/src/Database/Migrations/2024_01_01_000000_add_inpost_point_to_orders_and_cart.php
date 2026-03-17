<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Store selected locker on the cart shipping rate (during checkout)
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->string('inpost_point_id')->nullable()->after('method_description');
            $table->string('inpost_point_name')->nullable()->after('inpost_point_id');
            $table->text('inpost_point_address')->nullable()->after('inpost_point_name');
        });

        // Store selected locker permanently on the order
        Schema::table('orders', function (Blueprint $table) {
            $table->string('inpost_point_id')->nullable()->after('shipping_method');
            $table->string('inpost_point_name')->nullable()->after('inpost_point_id');
            $table->text('inpost_point_address')->nullable()->after('inpost_point_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->dropColumn(['inpost_point_id', 'inpost_point_name', 'inpost_point_address']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['inpost_point_id', 'inpost_point_name', 'inpost_point_address']);
        });
    }
};
