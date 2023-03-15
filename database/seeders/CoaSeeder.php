<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coa;

class CoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kode = array(
            '401',
            '402',
            '403',
            '601',
            '602',
            '603',
            '604',
            '605',
        );
        $nama = array(
            'Gaji Karyawan',
            'Gaji Ketua MPR',
            'Profit Trading',
            'Biaya Sekolah',
            'Bensin',
            'Parkir',
            'Makan Siang',
            'Makana Pokok Bulanan',
            
        );
        $category_id = array(
            1,1,2,3,4,4,5,5
        );
        $i = 0;
        while($i < 8){
            Coa::create([
                'kode'	=> $kode[$i],
                'nama'	=> $nama[$i],
                'category_id'	=> $category_id[$i]
            ]);
            $i++;
        }
    }
}
