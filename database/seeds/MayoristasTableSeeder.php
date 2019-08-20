<?php

use Illuminate\Database\Seeder;
use App\Mayorista;

class MayoristasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Mayorista::class, 10)->create();
    }
}
