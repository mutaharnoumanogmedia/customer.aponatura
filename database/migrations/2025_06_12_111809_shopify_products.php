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
        //
        Schema::create('shopify_products', function (Blueprint $table) {
            $table->id();

            // Shopify identifiers
            $table->string('shopify_id')->unique()->comment('Global ID: gid://shopify/Product/…');
            $table->string('handle')->nullable()->comment('URL handle/slug');

            // Basic product info
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->string('tags')->nullable()->comment('Comma-separated tags');
            $table->string("brand")->nullable()->comment('Brand name, if applicable');
            $table->boolean('show_to_customer')->default(false)->comment('Show to customer status');

            // Pricing
            $table->decimal('price', 12, 2)->comment('minVariantPrice.amount');
            $table->string('currency', 3)->comment('minVariantPrice.currencyCode');

            // Media & links
            $table->string('image_url')->nullable()->comment('featuredMedia.preview.image.originalSrc');
            $table->string('online_store_url')->nullable()->comment('onlineStorePreviewUrl');

            // Timestamps from Shopify (if available)
            $table->timestamp('shopify_created_at')->nullable();
            $table->timestamp('shopify_updated_at')->nullable();

            // Raw JSON payload (optional)
            $table->longText('raw_data')->nullable();

            // Local record timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
