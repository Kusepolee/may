<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config')->insert([
            [
                'name' => '男',            //开始: 性别
                'list' => 'gender',     
            ],[
                'name' => '女',
                'list' => 'gender',        //结束: 性别
            ],[
                'name' => '固定资产',
                'list' => 'resourceType',  // 开始: 资源类型
            ],[
                'name' => '原材料',
                'list' => 'resourceType',
            ],[
                'name' => '辅料',
                'list' => 'resourceType',
            ],[
                'name' => '工具',
                'list' => 'resourceType',
            ],[
                'name' => '消耗品',
                'list' => 'resourceType',
            ],[
                'name' => '半成品',
                'list' => 'resourceType',
            ],[
                'name' => '成品',
                'list' => 'resourceType',  // 结束: 资源类型
            ],[
                'name' => '公斤',           // 开始: 计量单位
                'list' => 'unit',
            ],[
                'name' => '升',
                'list' => 'unit',
            ],[
                'name' => '米',
                'list' => 'unit',
            ],[
                'name' => '平方米',
                'list' => 'unit',
            ],[
                'name' => '立方米',
                'list' => 'unit',
            ],[
                'name' => '件',
                'list' => 'unit',
            ],[
                'name' => '套',
                'list' => 'unit',         // 结束: 计量单位
            ],[
                'name' => '进货',
                'list' => 'resourceIn',   // 开始: 资源进
            ],[
                'name' => '领用归还',
                'list' => 'resourceIn',   
            ],[
                'name' => '回收或修好的',
                'list' => 'resourceIn',         
            ],[
                'name' => '自建自造',
                'list' => 'resourceIn',          
            ],[
                'name' => '非营业类获取', 
                'list' => 'resourceIn',    
            ],[
                'name' => '盘盈(多)',
                'list' => 'resourceIn',   // 结束: 资源进        
            ],[
                'name' => '领用',
                'list' => 'resourceOut',  // 开始: 资源出    
            ],[
                'name' => '出货',
                'list' => 'resourceOut',         
            ],[
                'name' => '损坏',
                'list' => 'resourceOut',         
            ],[
                'name' => '报废',
                'list' => 'resourceOut',         
            ],[
                'name' => '丢失',
                'list' => 'resourceOut',         
            ],[
                'name' => '盘亏(少)',
                'list' => 'resourceOut',  // 结束: 资源操作类型
            ],[
                'name' => '发票',
                'list' => 'financeOut',   // 开始: 支出单据类型      
            ],[
                'name' => '合同',
                'list' => 'financeOut',         
            ],[
                'name' => '收据',
                'list' => 'financeOut',         
            ],[
                'name' => '无票据',
                'list' => 'financeOut',         
            ],[
                'name' => '可统开',
                'list' => 'financeOut',   // 结束: 支出单据类型      
            ],[
                'name' => '现金',
                'list' => 'financetran',  // 开始: 收到资金类型       
            ],[
                'name' => '转账',
                'list' => 'financetran',         
            ],[
                'name' => '其他',
                'list' => 'financetran',  // 结束: 收到资金类型       
            ],[
                'name' => '塑料件',
                'list' => 'productType',  // 开始: 产品类型
            ],[
                'name' => '钣金件',
                'list' => 'productType',  
            ],[
                'name' => '灯具',
                'list' => 'productType',  
            ],[
                'name' => '组合件',
                'list' => 'productType',  
            ],[
                'name' => '其他',
                'list' => 'productType',  // 结束: 产品类型
            ],[
                'name' => '独占',
                'list' => 'productQuotaType',  // 开始: 资源消耗类型
            ],[
                'name' => '共用',
                'list' => 'productQuotaType',  
            ],[
                'name' => '折旧',
                'list' => 'productQuotaType',  
            ],[
                'name' => '附带',
                'list' => 'productQuotaType',  
            ],[
                'name' => '其他',
                'list' => 'productQuotaType',  // 结束: 资源消耗类型
            ],[
                'name' => '独占',
                'list' => 'productQuotaTimeType',  // 开始: 工时消耗类型
            ],[
                'name' => '合理调控',
                'list' => 'productQuotaTimeType', 
            ],[
                'name' => '附带',
                'list' => 'productQuotaTimeType', 
            ],[
                'name' => '其他',
                'list' => 'productQuotaTimeType',  // 结束: 工时消耗类型
            ],[
                'name' => '生产原料',
                'list' => 'resourceFor',  // 开始: 领用资源用途
            ],[
                'name' => '生产辅料',
                'list' => 'resourceFor',
            ],[
                'name' => '生产工具',
                'list' => 'resourceFor',
            ],[
                'name' => '生产消耗品',
                'list' => 'resourceFor',
            ],[
                'name' => '设备辅助',
                'list' => 'resourceFor',
            ],[
                'name' => '固定资产建设维护',
                'list' => 'resourceFor',
            ],[
                'name' => '办公用品',
                'list' => 'resourceFor',
            ],[
                'name' => '办公消耗品',
                'list' => 'resourceFor',
            ],[
                'name' => '对外',
                'list' => 'resourceFor',
            ],[
                'name' => '其他',
                'list' => 'resourceFor',  // 结束: 领用资源用途
            ],
        ]);
    }
}
