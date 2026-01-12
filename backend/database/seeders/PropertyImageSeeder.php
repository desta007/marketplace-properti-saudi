<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PropertyImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Uses high-quality real estate images from Unsplash for each property type
     */
    public function run(): void
    {
        $properties = Property::all();

        if ($properties->isEmpty()) {
            $this->command->warn('No properties found. Please run PropertySeeder first.');
            return;
        }

        // Real property images from Unsplash (using source.unsplash.com for direct access)
        // Images are selected to match Saudi Arabian architecture and real estate styles
        $imageCategories = [
            'villa' => [
                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&h=600&fit=crop', // Modern villa exterior
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop', // Luxury villa
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop', // Villa interior
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop', // Villa pool
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=600&fit=crop', // Villa living room
            ],
            'apartment' => [
                'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&h=600&fit=crop', // Modern apartment building
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=600&fit=crop', // Apartment interior
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop', // Apartment living space
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop', // Apartment room
            ],
            'compound' => [
                'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&h=600&fit=crop', // Compound villa
                'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=800&h=600&fit=crop', // Compound exterior
                'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=800&h=600&fit=crop', // Compound pool area
                'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=800&h=600&fit=crop', // Compound garden
            ],
            'land' => [
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&h=600&fit=crop', // Land plot
                'https://images.unsplash.com/photo-1628624747186-a941c476b7ef?w=800&h=600&fit=crop', // Commercial land
            ],
            'office' => [
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop', // Office building
                'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop', // Office interior
                'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&h=600&fit=crop', // Office space
            ],
        ];

        $count = 0;

        foreach ($properties as $property) {
            // Get images for this property type
            $images = $imageCategories[$property->type] ?? $imageCategories['apartment'];

            // Create directory for property
            $directory = "properties/{$property->id}";
            Storage::disk('public')->makeDirectory($directory);

            foreach ($images as $index => $imageUrl) {
                try {
                    // Download image with timeout
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 30,
                            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                        ],
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ]
                    ]);

                    $imageContent = @file_get_contents($imageUrl, false, $context);

                    if ($imageContent === false) {
                        // If download fails, create placeholder message
                        $this->command->warn("Could not download image for property {$property->id}, skipping");
                        continue;
                    }

                    // Save image
                    $filename = "image_{$index}.jpg";
                    $path = "{$directory}/{$filename}";

                    Storage::disk('public')->put($path, $imageContent);

                    // Create database record
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);

                    $count++;
                } catch (\Exception $e) {
                    $this->command->warn("Failed to create image for property {$property->id}: " . $e->getMessage());
                }
            }
        }

        $this->command->info("Created {$count} property images from Unsplash.");
    }
}
