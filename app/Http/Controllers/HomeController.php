<?php

namespace App\Http\Controllers;

Use App\Models\Coa;
Use App\Models\Category;
Use App\Models\Transaksi;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        function sumPendapatan($now,$last){
            if($now == $last){
                $last = date("Y-m-t", strtotime($last));
            }
            $creditsum = app('App\Http\Controllers\ReportController')->getDataAntara($now,$last,0)->sum('amount');
            $debitsum = app('App\Http\Controllers\ReportController')->getDataAntara($now,$last,1)->sum('amount');
            $pendapatan = $creditsum - $debitsum;
            return $pendapatan;
        }

        //pendapatan bulan ini
        $now = date('Y-m-01'); $last = date('Y-m-t');
        $pendapatanbln = sumPendapatan($now,$last);

        //data untuk grafik
        for($i=0; $i<12; $i++){
            $bulan[$i]['bln'] = date("Y-m-d",strtotime("-".$i." month", strtotime($now)));
            $bulan[$i]['sum'] = sumPendapatan($bulan[$i]['bln'],$bulan[$i]['bln']);
        }

        // dd($bulan);
        return view('home', compact('pendapatanbln','bulan'));

    }

}
