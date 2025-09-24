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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g., 'baaboo', 'biovana'
            $table->string('name'); // Display name

            // Branding info
            $table->string('domain')->unique(); // e.g., 'customer.biovana.com'
            $table->string("website_url")->nullable(); // e.g., 'https://biovana.com'
            $table->string('logo_path')->nullable(); // path to logo image
            $table->string('favicon_path')->nullable(); // optional favicon
            $table->string('primary_color')->nullable(); // hex like #00AAFF
            $table->string('secondary_color')->nullable();
            $table->string('title')->nullable(); // Site/page title override
            $table->string('slogan')->nullable(); // Short tagline
            $table->string('support_email')->nullable(); // Brand-specific contact


            $table->boolean('is_active')->default(true); // enable/disable brand
            $table->string('config')->nullable(); // optional extras, like feature toggles

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
