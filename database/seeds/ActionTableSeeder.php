<?php

use Illuminate\Database\Seeder;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            // ['type'=>'PO Manager','action' =>'All'],
            ['type'=>'PO Manager','action' =>'PO Manager Verification Pending'],
            ['type'=>'PO Manager','action' =>'PO Manager Verified'],
            ['type'=>'PO Manager','action' =>'PO Manager Rejected'],
            ['type'=>'PO Manager','action' =>'PO Manager Reapproved'],
            // ['type'=>'JC Manager','action' =>'All'],
            ['type'=>'JC Manager','action' =>'JC Pending'],
            ['type'=>'JC Manager','action' =>'JC Created'],
            // ['type'=>'Order','action' =>'All'],
            ['type'=>'Order','action' =>'Dispatched'],
            ['type'=>'Order','action' =>'Pending For Dispatch'],
            ['type'=>'Order','action' =>'Partial Dispatch'],
            ['type'=>'Order','action' =>'Hold'],
            ['type'=>'Order','action' =>'Cancelled'],
            ['type'=>'Order','action' =>'In Production'],
            ['type'=>'Order','action' =>'Production Complete'],
            // ['type'=>'Invoice','action' =>'All'],
            ['type'=>'Invoice','action' =>'Within State'],
            ['type'=>'Invoice','action' =>'Inter State'],
            // ['type'=>'Invoice','action' =>'GST'],
            // ['type'=>'Invoice','action' =>'Job Works']);
            // ['type'=>'Invoice','action' =>'Export']);

         DB::table('actions')->insert($data);
    }
}
