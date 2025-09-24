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
        Schema::create('many_chat_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('subscriber_id')->unique()->index();

            $table->string('status')->nullable();             // active / inactive
            $table->string('page_id')->nullable();
            $table->json('user_refs')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('locale')->nullable();
            $table->string('language')->nullable();
            $table->string('timezone')->nullable();

            $table->string('live_chat_url')->nullable();
            $table->text('last_input_text')->nullable();

            $table->boolean('optin_phone')->nullable();
            $table->string('phone')->nullable();

            $table->boolean('optin_email')->nullable();
            $table->string('email')->nullable();
            $table->string('tag')->nullable();
            $table->timestamp('subscribed_at')->nullable();
            
            $table->timestamp('last_interaction_at')->nullable();
            $table->timestamp('ig_last_interaction_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('ig_last_seen_at')->nullable();

            $table->boolean('is_followup_enabled')->default(false);

            $table->string('ig_username')->nullable();
            $table->string('ig_id')->nullable();

            $table->string('whatsapp_phone')->nullable();
            $table->boolean('optin_whatsapp')->nullable();

            // Full JSON blobs for flexibility
            $table->json('custom_fields')->nullable();
            $table->json('tags')->nullable();

            // Commonly-needed flattened values from custom_fields (optional but handy)
            $table->string('cf_user_name')->nullable();
            $table->string('cf_user_email')->nullable();
            $table->string('cf_register_status')->nullable();
            $table->string('cf_user_mobile_number')->nullable();
            $table->string('cf_user_first_name')->nullable();
            $table->string('cf_user_last_name')->nullable();
            $table->timestamp('cf_current_time_minus_1_day')->nullable();
            $table->timestamp('cf_last_escalation_sent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('many_chat_subscribers');
    }
};
