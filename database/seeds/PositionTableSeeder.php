<?php

use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
            [
                'name' => 'root',
                'order' => 0,
            ],[
                'name' => '董事长',
                'order' => 1,
            ],[
                'name' => '总经理',
                'order' => 2,
            ],[
                'name' => '总管',
                'order' => 3,
            ],[
                'name' => '副总经理',
                'order' => 4,
            ],[
                'name' => '总工程师',
                'order' => 5,
            ],[
                'name' => '总监',
                'order' => 6,
            ],[
                'name' => '经理',
                'order' => 7,
            ],[
                'name' => '主任',
                'order' => 8,
            ],[
                'name' => '班长',
                'order' => 9,
            ],[
                'name' => '组长',
                'order' => 10,
            ],[
                'name' => '工程师',
                'order' => 11,
            ],[
                'name' => '员工',
                'order' => 12,
            ],
        ]);
    }
}
