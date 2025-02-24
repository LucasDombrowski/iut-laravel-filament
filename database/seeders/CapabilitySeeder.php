<?php

namespace Database\Seeders;

use App\Enums\CapabilityEnum;
use App\Models\Capability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CapabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(CapabilityEnum::cases() as $capability) {
            Capability::factory()->create([
                'slug' => $capability->value,
                'label' => $capability->label(),
            ]);
        }
    }
}
