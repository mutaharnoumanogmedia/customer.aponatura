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
        Schema::create('whatsapp_support_chat_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('whatsapp_session_chat_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->boolean('review_sent')->default(false);
            $table->unsignedTinyInteger('review_stars')->nullable();
            $table->text('review_note')->nullable();
            $table->longText('chat_log')->nullable();
            $table->text("chat_started_at")->nullable();
            $table->text("chat_ended_at")->nullable();
            $table->unsignedBigInteger('total_messages')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_support_chat_logs');
    }
};
