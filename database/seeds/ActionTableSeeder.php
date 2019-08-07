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

        \App\Actions::create([
                'id' =>1,
                'type' => 'PO Manager',
                'action' =>'PO Manager Verification Pending',
            ]);

        \App\Actions::create([
                'id' =>2,
                'type' => 'PO Manager',
                'action' =>'PO Manager Verified',
            ]);

        \App\Actions::create([
                'id' =>3,
                'type' => 'PO Manager',
                'action' =>'PO Manager Rejected',
            ]);

        \App\Actions::create([
                'id' =>4,
                'type' => 'PO Manager',
                'action' =>'PO Manager Reapproved',
            ]);

        \App\Actions::create([
                'id' =>5,
                'type' => 'JC Manager',
                'action' =>'JC Pending',
            ]);

        \App\Actions::create([
                'id' =>6,
                'type' => 'JC Manager',
                'action' =>'JC Created',
            ]);

        \App\Actions::create([
                'id' =>7,
                'type' => 'Order',
                'action' =>'Dispatched',
            ]);

        \App\Actions::create([
                'id' =>8,
                'type' => 'Order',
                'action' =>'Pending For Dispatch',
            ]);

        \App\Actions::create([
                'id' =>9,
                'type' => 'Order',
                'action' =>'Partial Dispatch',
            ]);

        \App\Actions::create([
                'id' =>10,
                'type' => 'Order',
                'action' =>'Hold',
            ]);

        \App\Actions::create([
                'id' =>11,
                'type' => 'Order',
                'action' =>'Cancelled',
            ]);

        \App\Actions::create([
                'id' =>12,
                'type' => 'Order',
                'action' =>'In Production',
            ]);

        \App\Actions::create([
                'id' =>13,
                'type' => 'Order',
                'action' =>'Production Complete',
            ]);

        \App\Actions::create([
                'id' =>14,
                'type' => 'Invoice',
                'action' =>'Within State',
            ]);

        \App\Actions::create([
                'id' =>15,
                'type' => 'Invoice',
                'action' =>'Inter State',
            ]);

        // $data = array(
            // ['type'=>'PO Manager','action' =>'All'],
            // ['type'=>'PO Manager','action' =>'PO Manager Verification Pending'],
            // ['type'=>'PO Manager','action' =>'PO Manager Verified'],
            // ['type'=>'PO Manager','action' =>'PO Manager Rejected'],
            // ['type'=>'PO Manager','action' =>'PO Manager Reapproved'],
            // ['type'=>'JC Manager','action' =>'All'],
            // ['type'=>'JC Manager','action' =>'JC Pending'],
            // ['type'=>'JC Manager','action' =>'JC Created'],
            // ['type'=>'Order','action' =>'All'],
            // ['type'=>'Order','action' =>'Dispatched'],
            // ['type'=>'Order','action' =>'Pending For Dispatch'],
            // ['type'=>'Order','action' =>'Partial Dispatch'],
            // ['type'=>'Order','action' =>'Hold'],
            // ['type'=>'Order','action' =>'Cancelled'],
            // ['type'=>'Order','action' =>'In Production'],
            // ['type'=>'Order','action' =>'Production Complete'],
            // ['type'=>'Invoice','action' =>'All'],
            // ['type'=>'Invoice','action' =>'Within State'],
            // ['type'=>'Invoice','action' =>'Inter State']);
            // ['type'=>'Invoice','action' =>'GST'],
            // ['type'=>'Invoice','action' =>'Job Works']);
            // ['type'=>'Invoice','action' =>'Export']);

         // DB::table('actions')->insert($data);
    }
}
