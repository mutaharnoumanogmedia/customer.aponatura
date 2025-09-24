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
        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedInteger('brand_id')->nullable()->after('id');
            $table->string('terms_condition')->nullable();
            $table->string('privacy_policy')->nullable();
            $table->string('imprint')->nullable();
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
