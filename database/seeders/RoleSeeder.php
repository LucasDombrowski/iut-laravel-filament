<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Capability;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(RoleEnum::cases() as $role) {
            $roleModel = Role::factory()->create(
                [
                    'slug' => $role->value,
                    'name' => $role->label(),
                ]
            )->first();
            foreach($role->capabilities() as $capability) {
                $capabilityModel = Capability::where('slug', $capability->value)->first();
                $roleModel->capabilities()->attach($capabilityModel);
            }
        }
    }
}
