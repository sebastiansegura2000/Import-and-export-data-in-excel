<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Sebastian Segura Castellanos',
            'email'=>'sebastiansegura260@gmail.com',
            'password' => Hash::make('sebastiansegura260@gmail.com')
        ]);



        User::create([
            'name'=>'Walter white',
            'email'=>'walter@gmail.com',
            'password' => Hash::make('walter@gmail.com')
        ]);

        User::create([
            'name'=>'Gus Fring',
            'email'=>'GusFring@gmail.com',
            'password' => Hash::make('GusFring@gmail.com')
        ]);


        User::create([
            'name'=>'Saul Goodman',
            'email'=>'SaulGoodman@gmail.com',
            'password' => Hash::make('SaulGoodman@gmail.com')
        ]);



        User::create([
            'name'=>'jesse pinkman',
            'email'=>'jessepinkman@gmail.com',
            'password' => Hash::make('jessepinkman@gmail.com')
        ]);


        User::create([
            'name'=>'goku',
            'email'=>'goku@gmail.com',
            'password' => Hash::make('goku@gmail.com')
        ]);
    }
}
