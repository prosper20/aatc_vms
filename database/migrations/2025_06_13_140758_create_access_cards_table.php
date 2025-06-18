<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('access_cards', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->enum('access_level', ['low', 'medium', 'master'])->default('low');
            $table->string('issued_to')->nullable(); // e.g. Visitor #10, Staff #2
            $table->timestamp('issued_at')->nullable();
            $table->boolean('is_issued')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('access_cards');
    }
};
