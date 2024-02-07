<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Staffs;
use App\Models\debtRecord;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Khaing Kyaw Min',
        //     'email' => 'khaingkyawmin1991@gmail.com',
        //     'password'=>Hash::make('opensesame'),
        //     'role'=>'admin'
        // ]);

       $faker=Faker::create();
       $staffs=Staffs::inRandomOrder()->take(30)->get();
       foreach($staffs as $staff){
        for($i=0; $i<2;$i++){
            debtRecord::create([
                'staff_id'=>$staff->id,
                'type'=>$faker->randomElement(['loan','payment']),
                'amount'=>$faker->randomElement([100000,300000,500000]),
                'description'=>$faker->sentence

            ]);
        }
       }
    }
}
