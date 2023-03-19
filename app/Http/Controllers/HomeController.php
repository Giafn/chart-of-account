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
        $bulan = (int)date('m');
        $tahun = (int)date('Y');

        $totaldebit = Homecontroller::getDebitMonth($bulan,$tahun)['sum'];
        $totalcredit = Homecontroller::getCreditMonth($bulan,$tahun)['sum'];

        $Profitbulan = $totalcredit - $totaldebit;
        

        //data untuk chart
        $date = date('Y-m-d');
        $thismoth = date('m');
        $threemoth = date('m', strtotime("-2 months", strtotime($date)));
        $secondmoth = date('m', strtotime("-1 months", strtotime($date)));
        $bln = array(
            (int)$threemoth,
            (int)$secondmoth,
            (int)$thismoth,
        );
        for($i=0; $i<3; $i++){
            $summonth[$i] = Homecontroller::getCreditMonth($bln[$i],$tahun)['sum'] - Homecontroller::getDebitMonth($bln[$i],$tahun)['sum'];
        }

        return view('home', compact('Profitbulan','bln','summonth'));

    }

    public function getDebitMonth($bulan, $tahun)
    {
        $debitIndicator = Category::where('indicator', 1)->get();

        global $debitId;
        $i = 0;
        // loop untuk masukin id ke array
        foreach($debitIndicator as $item){
            $debitId[$i] = $item->id;
            $i++;
        }
        $debitData = Transaksi::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->whereHas('coa', function ($query) {
                            global $debitId;
                            return $query->whereIn('category_id', $debitId);
                        })->get();
        $sum = $debitData->sum('nominal');
        $data = array(
            'data' => $debitData,
            'sum' => $sum
        );
        return $data;
    }

    public function getCreditMonth($bulan, $tahun)
    {
        $creditIndicator = Category::where('indicator', 0)->get();

        global $creditId;
        $i = 0;
        foreach($creditIndicator as $item){
            $creditId[$i] = $item->id;
            $i++;
        }

        $creditData = Transaksi::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->whereHas('coa', function ($query) {
                            global $creditId;
                            return $query->whereIn('category_id', $creditId);
                        })->get();
        $sum = $creditData->sum('nominal');
        $data = array(
            'data' => $creditData,
            'sum' => $sum
        );
        return $data;
    }
}
