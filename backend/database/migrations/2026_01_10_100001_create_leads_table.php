<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('seeker_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');

            // Seeker info (can be filled without login)
            $table->string('seeker_name');
            $table->string('seeker_phone', 20);
            $table->string('seeker_email')->nullable();
            $table->text('message')->nullable();

            // Lead tracking
            $table->enum('source', ['whatsapp', 'phone', 'chat', 'form', 'viewing_request'])->default('form');
            $table->enum('status', ['new', 'contacted', 'qualified', 'viewing_scheduled', 'converted', 'lost'])->default('new');
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['agent_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
