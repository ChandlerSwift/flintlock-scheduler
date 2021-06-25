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

        $flintlock = new User;
        $flintlock->name = "Flintlock";
        $flintlock->email = "flintlock@manypoint.org";
        $flintlock->password = Hash::make("MPSC1946");
        $flintlock->admin = true;
        $flintlock->save();

        $buckskin = new User;
        $buckskin->name = "Buckskin";
        $buckskin->email = "buckskin@manypoint.org";
        $buckskin->password = Hash::make("MPSC1946");
        $buckskin->admin = false;
        $buckskin->save();

        $tenChiefs = new User;
        $tenChiefs->name = "Ten Chiefs";
        $tenChiefs->email = "tenchiefs@manypoint.org";
        $tenChiefs->password = Hash::make("MPSC1946");
        $tenChiefs->admin = false;

        $administration = new User;
        $administration->name = "Admin";
        $administration->email = "office@manypoint.org";
        $administration->password = Hash::make("MPSC1946");
        $administration->admin = false;
        $administration->save();

        $voyageur = new User;
        $voyageur->name = "Voyageur";
        $voyageur->email = "voyageur@manypoint.org";
        $voyageur->password = Hash::make("MPSC1946");
        $voyageur->admin = false;
        $voyageur->save();

        $campingDirector = new User;
        $campingDirector->name = "Camping Director";
        $campingDirector->email = "eyingst@northernstar.org";
        $campingDirector->password = Hash::make("MPSC1946");
        $campingDirector->admin = false;
        $campingDirector->save();
        // ...

    }
}
