<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use Faker\Factory as Faker;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

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

        for($i = 1; $i <= 50; $i++){
 
            // insert data ke table pegawai menggunakan Faker
          Transaksi::insert([
            'coa_id'	=> $faker->numberBetween(1, 8),
            'desc'	=> $faker->sentence(3), 
            'nominal'	=> $faker->numberBetween(30000, 5000000),
            'created_at' => $faker->dateTimeBetween('-1 years', '+0 day')
          ]);

      }
    }
}
