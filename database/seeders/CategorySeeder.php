<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nama = array(
            'Salary',
            'Other Income',
            'Family Expense',
            'Transport Expense',
            'Meal Expense'
        );
        $indicator = array(
            1,1,0,0,0
        );
        $i = 0;
        while($i < 5){
            Category::create([
                'nama'	=> $nama[$i],
                'indicator'	=> $indicator[$i]
            ]);
            $i++;
        }
    }
}
