<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coa_id = array(
            1,2,5
        );
        $desc = array(
            'Gaji Di Persuhaan A',
            'Gaji Ketum',
            'Bensin Anak'

        );
        $nominal = array(
            5000000,
            7000000,
            25000
        );

        $i = 0;
        while($i < 3){
            Transaksi::create([
                'coa_id'	=> $coa_id[$i],
                'desc'	=> $desc[$i],
                'nominal'	=> $nominal[$i],
            ]);
            $i++;
        }
    }
}
