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
        Schema::create(config('nova_mail_manager.table_name'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->string('subject');
            $table->longText('body');
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->string('reply_to')->nullable();
            $table->json('attachments')->nullable();
            $table->json('variables')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('nova_mail_manager.table_name'));
    }
};
