<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conditions')->delete();
        DB::table('conditions')->insert([
            ['id'=>1,'name'=>'良好','sort_order'=>1],
            ['id'=>2,'name'=>'目立った傷や汚れなし','sort_order'=>2],
            ['id'=>3,'name'=>'やや傷や汚れあり','sort_order'=>3],
            ['id'=>4,'name'=>'状態が悪い','sort_order'=>4],
        ]);
    }
}

