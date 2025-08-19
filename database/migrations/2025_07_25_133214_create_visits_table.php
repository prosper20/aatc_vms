<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('set null');
            $table->date('visit_date');
            $table->string('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('unique_code')->unique();
            $table->string('floor_of_visit')->nullable();

            // Gate verification
            $table->timestamp('arrived_at_gate')->nullable();
            $table->boolean('verification_passed')->default(false);
            $table->text('verification_message')->nullable();
            $table->string('verified_by')->nullable();
            $table->enum('mode_of_arrival', ['vehicle', 'foot'])->nullable();
            $table->string('plate_number')->nullable();
            $table->enum('vehicle_type', ['drop-off', 'wait'])->nullable();
            $table->text('inquiry')->nullable();
            $table->text('notes')->nullable();

            // Check-in / Check-out
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->string('checkin_by')->nullable();
            $table->string('checkout_by')->nullable();
            $table->boolean('is_checked_in')->default(false);
            $table->boolean('is_checked_out')->default(false);

            // Access card
            $table->foreignId('access_card_id')->nullable()->constrained('access_cards')->onDelete('set null');
            $table->timestamp('card_issued_at')->nullable();
            $table->timestamp('card_retrieved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('visits');
    }
};
