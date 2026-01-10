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
        Schema::table('users', function (Blueprint $table) {
            // Agent verification fields
            $table->string('rega_license_number', 50)->nullable()->after('avatar');
            $table->string('rega_license_document')->nullable()->after('rega_license_number');
            $table->enum('agent_status', ['pending', 'verified', 'rejected'])->nullable()->after('rega_license_document');
            $table->text('agent_rejection_reason')->nullable()->after('agent_status');
            $table->timestamp('agent_verified_at')->nullable()->after('agent_rejection_reason');

            // WhatsApp number for direct contact
            $table->string('whatsapp_number', 20)->nullable()->after('phone');

            // Index for agent queries
            $table->index('agent_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['agent_status']);
            $table->dropColumn([
                'rega_license_number',
                'rega_license_document',
                'agent_status',
                'agent_rejection_reason',
                'agent_verified_at',
                'whatsapp_number',
            ]);
        });
    }
};
