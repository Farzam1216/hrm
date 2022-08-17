<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultAccessLevels= [
            ['id'=>0,'name'=>'Self'],
            ['id'=>1,'name'=>'Direct'],
            ['id'=>2,'name'=>'Direct and Indirect'],
            ['id'=>3,'name'=>'Specific Employees'],
            ['id'=>4,'name'=>'All Employees']];
        DB::table('access_levels')->insert($defaultAccessLevels);
    }
}
