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
        Schema::table('brands', function (Blueprint $table) {
            //
            $table->string('shopify_id')->nullable();
            $table->string('shopify_api_key')->nullable();
            $table->string('shopify_api_secret')->nullable();
            $table->string('shopify_api_url')->nullable();
            $table->string('shopify_api_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            //
            $table->dropColumn(['shopify_id', 'shopify_api_key', 'shopify_api_secret', 'shopify_api_url', 'shopify_api_token']);
        });
    }
};
