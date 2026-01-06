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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->foreignId('district_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('title_en');
            $table->string('title_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            
            $table->enum('type', ['villa', 'apartment', 'compound', 'land', 'office']);
            $table->enum('purpose', ['sale', 'rent']);
            $table->decimal('price', 15, 2);
            $table->integer('area_sqm')->nullable();
            $table->tinyInteger('bedrooms')->nullable();
            $table->tinyInteger('bathrooms')->nullable();
            
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('features')->nullable();
            
            $table->string('rega_ad_license', 50)->nullable();
            $table->enum('status', ['pending', 'active', 'rejected', 'sold'])->default('pending');
            $table->unsignedInteger('view_count')->default(0);
            
            $table->timestamps();
            
            $table->index(['city_id', 'type', 'purpose']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
