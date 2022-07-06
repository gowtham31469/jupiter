<?php

use Illuminate\Database\Seeder;

class MachineExcessDetails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('machine_excess_details')->truncate();


        $data = [
            [
                'machine_id' => 2,
                'min_range' => 0,
                'max_range' => 1500, 
                'excess' => 100, 
                'excess_unit' => 'pieces'
            ],
            [
                'machine_id' => 2,
                'min_range' => 1501,
                'max_range' => 7000, 
                'excess' => 7.5, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 2,
                'min_range' => 7001,
                'max_range' => 8000, 
                'excess' => 7, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 2,
                'min_range' => 8001,
                'max_range' => 10000, 
                'excess' => 6.5, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 2,
                'min_range' => 10001,
                'max_range' => 20000, 
                'excess' => 5, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 2,
                'min_range' => 20001,
                'max_range' => 30000, 
                'excess' => 3, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 2,
                'min_range' => 30001,
                'max_range' => 40000, 
                'excess' => 2.5, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 2,
                'min_range' => 40001,
                'max_range' => 100000, 
                'excess' => 2, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 0,
                'max_range' => 3000, 
                'excess' => 10, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 3001,
                'max_range' => 7000, 
                'excess' => 9, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 7001,
                'max_range' => 10000, 
                'excess' => 8, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 10001,
                'max_range' => 15000, 
                'excess' => 7, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 15001,
                'max_range' => 20000, 
                'excess' => 6, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 20001,
                'max_range' => 30000, 
                'excess' => 4, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 30001,
                'max_range' => 40000, 
                'excess' => 3, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 40001,
                'max_range' => 50000, 
                'excess' => 2.5, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 3,
                'min_range' => 50001,
                'max_range' => 100000, 
                'excess' => 2, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 4,
                'min_range' => 0,
                'max_range' => 1500, 
                'excess' => 100, 
                'excess_unit' => 'pieces'
            ],
            [
                'machine_id' => 4,
                'min_range' => 1501,
                'max_range' => 3000, 
                'excess' => 7, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 4,
                'min_range' => 3001,
                'max_range' => 7000, 
                'excess' => 5, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 4,
                'min_range' => 7001,
                'max_range' => 10000, 
                'excess' => 4, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 4,
                'min_range' => 10001,
                'max_range' => 20000, 
                'excess' => 3, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 4,
                'min_range' => 20001,
                'max_range' => 30000, 
                'excess' => 2.5, 
                'excess_unit' => 'percentage'
            ],
            [
                'machine_id' => 4,
                'min_range' => 30001,
                'max_range' => 50000, 
                'excess' => 2, 
                'excess_unit' => 'percentage'
            ],

        ];

        DB::table('machine_excess_details')->insert($data);

    }
}
