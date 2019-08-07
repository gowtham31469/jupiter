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

        \App\Action::create([
                'id' =>1,
                'type' => 'PO Manager',
                'action' =>'PO Manager Verification Pending',
            ]);

        \App\Action::create([
                'id' =>2,
                'type' => 'PO Manager',
                'action' =>'PO Manager Verified',
            ]);

        \App\Action::create([
                'id' =>3,
                'type' => 'PO Manager',
                'action' =>'PO Manager Rejected',
            ]);

        \App\Action::create([
                'id' =>4,
                'type' => 'PO Manager',
                'action' =>'PO Manager Reapproved',
            ]);

        \App\Action::create([
                'id' =>5,
                'type' => 'JC Manager',
                'action' =>'JC Pending',
            ]);

        \App\Action::create([
                'id' =>6,
                'type' => 'JC Manager',
                'action' =>'JC Created',
            ]);

        \App\Action::create([
                'id' =>7,
                'type' => 'Order',
                'action' =>'Dispatched',
            ]);

        \App\Action::create([
                'id' =>8,
                'type' => 'Order',
                'action' =>'Pending For Dispatch',
            ]);

        \App\Action::create([
                'id' =>9,
                'type' => 'Order',
                'action' =>'Partial Dispatch',
            ]);

        \App\Action::create([
                'id' =>10,
                'type' => 'Order',
                'action' =>'Hold',
            ]);

        \App\Action::create([
                'id' =>11,
                'type' => 'Order',
                'action' =>'Cancelled',
            ]);

        \App\Action::create([
                'id' =>12,
                'type' => 'Order',
                'action' =>'In Production',
            ]);

        \App\Action::create([
                'id' =>13,
                'type' => 'Order',
                'action' =>'Production Complete',
            ]);

        \App\Action::create([
                'id' =>14,
                'type' => 'Invoice',
                'action' =>'Within State',
            ]);

        \App\Action::create([
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

         // DB::table('Action')->insert($data);
    }
}
