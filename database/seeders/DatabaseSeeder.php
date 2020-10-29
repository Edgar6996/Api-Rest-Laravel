<?php

namespace Database\Seeders;

use App\Enums\TiposUsuarios;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         # Creamos los usuarios basicos
        User::create( [
            'name' => "Administrador",
            'email' => "admin@comedor.app",
            'username' => "admin",
            'email_verified_at' => now(),
            'password' => \Hash::make(env("PSW_USER_ADMIN","admin")),
            'remember_token' => \Str::random(10),
            'rol' => TiposUsuarios::ADMINISTRADOR
        ]);

        // Root User
        User::create( [
            'name' => "Super Usuario",
            'email' => "root@comedor.app",
            'username' => "r00t",
            'email_verified_at' => now(),
            'password' => \Hash::make(env("PSW_USER_ROOT","root")),
            'remember_token' => \Str::random(10),
            'rol' => TiposUsuarios::ROOT
        ]);




    }
}
