<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('message');
            $table->longText('response')->nullable();
            $table->string('ai_provider')->nullable();
            $table->string('ai_model')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->float('processing_time')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['user_id', 'is_internal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};