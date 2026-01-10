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
     */
    public function run(): void
    {
        $properties = Property::all();

        if ($properties->isEmpty()) {
            $this->command->warn('No properties found. Please run PropertySeeder first.');
            return;
        }

        // Sample property images from picsum.photos (placeholder images)
        $imageCategories = [
            'villa' => [
                'https://picsum.photos/seed/villa1/800/600',
                'https://picsum.photos/seed/villa2/800/600',
                'https://picsum.photos/seed/villa3/800/600',
                'https://picsum.photos/seed/villainterior1/800/600',
                'https://picsum.photos/seed/villainterior2/800/600',
            ],
            'apartment' => [
                'https://picsum.photos/seed/apt1/800/600',
                'https://picsum.photos/seed/apt2/800/600',
                'https://picsum.photos/seed/apt3/800/600',
                'https://picsum.photos/seed/aptinterior1/800/600',
            ],
            'compound' => [
                'https://picsum.photos/seed/compound1/800/600',
                'https://picsum.photos/seed/compound2/800/600',
                'https://picsum.photos/seed/compound3/800/600',
                'https://picsum.photos/seed/compoundpool/800/600',
            ],
            'land' => [
                'https://picsum.photos/seed/land1/800/600',
                'https://picsum.photos/seed/land2/800/600',
            ],
            'office' => [
                'https://picsum.photos/seed/office1/800/600',
                'https://picsum.photos/seed/office2/800/600',
                'https://picsum.photos/seed/office3/800/600',
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
                    // Download image
                    $imageContent = @file_get_contents($imageUrl);

                    if ($imageContent === false) {
                        // If download fails, create a simple placeholder
                        $this->command->warn("Could not download image for property {$property->id}, creating placeholder");
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

        $this->command->info("Created {$count} property images.");
    }
}
