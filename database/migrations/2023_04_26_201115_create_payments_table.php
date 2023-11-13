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
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('email');
            $table->string('stripe_id');
            $table->string('url');
            $table->string('computer_image_url')->nullable();
            $table->string('phone_image_url')->nullable();
            $table->json('roast')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
