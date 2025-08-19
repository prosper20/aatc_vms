<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('access_cards', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->enum('card_type', ['access_card', 'visitor_pass'])->default('visitor_pass');
            $table->enum('access_level', ['low', 'medium', 'master'])->default('low');
            $table->string('issued_to')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->string('issued_by')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_issued')->default(false);
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('access_cards');
    }
};
