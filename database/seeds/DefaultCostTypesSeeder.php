<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultCostTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $costTypes = [
            'L' =>  'Labor',
            'S' => 'Subcontractor',
            'M' => 'Material',
            'E' => 'Equipment'
        ];

        foreach ($costTypes as $i => $costType) {
            $exists = DB::table('cost_types')
                ->where('name', $costType)
                ->where('abbrevation', $i)
                ->exists();

            if (!$exists) {
                DB::table('cost_types')->insert([
                    'name' => $costType,
                    'abbrevation' => $i,
                    'created_by' => '',
                    'last_update_by' => '',
                    'created_date' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
