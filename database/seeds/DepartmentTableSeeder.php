<?php

use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('departments')->insert([
                [
                'parentid' => 0,
                'name'     => 'root',
                'order'    => 1,
                'code'     => '1',
                ],[
                'parentid' => 1,
                'name'     => '公司内部',
                'order'    => 2,
                'code'     => '1-2',
                ],[
                'parentid' => 2,
                'name'     => '总经理',
                'order'    => 3,
                'code'     => '1-2-3',
                ],[
                'parentid' => 3,
                'name'     => '副总经理',
                'order'    => 4,
                'code'     => '1-2-3-4',
                ],[
                'parentid' => 4,
                'name'     => '市场部',
                'order'    => 5,
                'code'     => '1-2-3-4-5',
                ],[
                'parentid' => 4,
                'name'     => '运营部',
                'order'    => 6,
                'code'     => '1-2-3-4-6',
                ],[
                'parentid' => 4,
                'name'     => '生产部',
                'order'    => 7,
                'code'     => '1-2-3-4-7',
                ],[
                'parentid' => 4,
                'name'     => '资源部',
                'order'    => 8,
                'code'     => '1-2-3-4-8',
                ],[
                'parentid' => 4,
                'name'     => '技术部',
                'order'    => 9,
                'code'     => '1-2-3-4-9',
                ],[
                'parentid' => 4,
                'name'     => '监察部',
                'order'    => 10,
                'code'     => '1-2-3-4-10',
                ],[
                'parentid' => 1,
                'name'     => '客户',
                'order'    => 11,
                'code'     => '1-11',
                ],[
                'parentid' => 1,
                'name'     => '供应商',
                'order'    => 12,
                'code'     => '1-12',
                ],[
                'parentid' => 1,
                'name'     => '政务部门',
                'order'    => 13,
                'code'     => '1-13',
                ],
        ]);
    }
}
