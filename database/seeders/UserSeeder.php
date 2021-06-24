<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isaac = new User;
        $isaac->name = "Isaac Swift";
        $isaac->email = "ijs@swiftgang.net";
        $isaac->password = Hash::make("hunter2");
        $isaac->admin = true;
        $isaac->save();
        // ...
    }
}
