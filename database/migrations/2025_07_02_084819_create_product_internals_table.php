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
        Schema::create('product_internals', function (Blueprint $table) {
            $table->id();
            // General identifiers
            $table->string('sku')->unique()->nullable();
            $table->string('internal_code')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('location')->nullable(); // warehouse/bin for physical

            // Inventory
            $table->integer('stock_quantity')->default(0)->nullable(); // Not used for digital usually
            $table->integer('min_stock_threshold')->nullable();
            $table->integer('max_stock_threshold')->nullable();
            $table->string('unit')->nullable();

            // Pricing
            $table->float('price', 10, 2)->nullable();
            $table->float('wholesale_price', 10, 2)->nullable();
            $table->float('retail_price', 10, 2)->nullable();

            // Type: physical or digital
            $table->string('product_type')->default('physical'); // physical, digital, service, etc.

            // Digital product fields
            $table->string('file_path')->nullable();         // storage path
            $table->string('download_url')->nullable();      // public-facing link if needed
            $table->string('license_key')->nullable();       // optional license or serial
            $table->integer('max_downloads')->nullable();    // limit number of downloads
            $table->timestamp('access_expires_at')->nullable(); // expiration for access

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_discontinued')->default(false);
            $table->string('status')->default('in_stock'); // in_stock, out_of_stock, archived, etc.

            // Dates
            $table->timestamp('received_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Meta / notes
            $table->text('notes')->nullable();
            $table->text('attributes')->nullable(); // dynamic specs like resolution, format

            //product picture
            $table->string('image_path')->nullable(); // storage path for product image

            // Admin tracking
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_internals');
    }
};
