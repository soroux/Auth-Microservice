<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Type::create([
            'name'=>'email and password',
        ]);
        Type::create([
            'name'=>'phone and password',
        ]);
        Type::create([
            'name'=>'username and password',
        ]);
        Type::create([
            'name'=>'email sso',
        ]);
        Type::create([
            'name'=>'phone sso',
        ]);
        Type::create([
            'name'=>'google',
        ]);

    }
}
