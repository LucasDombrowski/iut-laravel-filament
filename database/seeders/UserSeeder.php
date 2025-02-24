<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Address;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create(
            [
                "email"=>"admin@exemple.com",
                "role_id"=>Role::where('slug', RoleEnum::Admin->value)->first()->id,
            ]
        );

        User::factory(10)
        ->has(Address::factory()->count(2))
        ->has(Order::factory(3))
        ->create([
            "role_id"=>Role::where('slug', RoleEnum::User->value)->first()->id,
        ]);
    }
}
