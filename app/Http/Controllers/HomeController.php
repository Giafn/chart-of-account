<?php

namespace App\Http\Controllers;



class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        function SumPendapatan($now,$last){
            if ($now == $last) {
                $last = date("Y-m-t", strtotime($last));
            }
            $report = new ReportController;
            $creditsum = $report->GetDataAntara($now,$last,0)->sum('amount');
            $debitsum = $report->GetDataAntara($now,$last,1)->sum('amount');
            $pendapatan = $creditsum - $debitsum;
            return $pendapatan;
        }

        $now = date('Y-m-01'); $last = date('Y-m-t');
        $pendapatanbln = SumPendapatan($now,$last);

        for ($i=0; $i<12; $i++) {
            $bulan[$i]['bln'] = date("Y-m-d",strtotime("-".$i." month", strtotime($now)));
            $bulan[$i]['sum'] = SumPendapatan($bulan[$i]['bln'],$bulan[$i]['bln']);
        }

        return view('home', compact('pendapatanbln','bulan'));

    }

}
