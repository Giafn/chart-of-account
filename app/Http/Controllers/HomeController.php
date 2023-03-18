<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
Use App\Models\Coa;
Use App\Models\Category;
Use App\Models\Transaksi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bulan = (int)date('m');
        $tahun = (int)date('Y');

        $totaldebit = Homecontroller::getDebitMonth($bulan,$tahun)['sum'];
        $totalcredit = Homecontroller::getCreditMonth($bulan,$tahun)['sum'];

        $Profitbulan = $totalcredit - $totaldebit;
        $Profittahun = Homecontroller::getDataYear($tahun)['total'];
        

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

        // dd($summonth);
        $asu = Transaksi::whereYear('created_at', $tahun)
                        ->whereHas('coa', function ($query) {
                            global $debitId;
                            return $query->whereIn('category_id', $debitId);
                        })->get();

        return view('home', compact('Profitbulan','Profittahun','bln','summonth'));

    }

    public function report(Request $request)
    {
        
        $bulan = (int)date('m');
        $tahun = (int)date('Y');
        $id = 1;

        // bikin list kategory
        $Category = Category::get()->all();
        $in = 0;
        $ex = 0;
        foreach ($Category as $key) {
            //untuk kategory type income
            if($key->indicator == 0){
                $listCategory['income'][$in] = $key->nama;
                $in++;
            }else{
                $listCategory['expense'][$ex] = $key->nama;
                $ex++;
            }
        }

        // dd($listCategory);
        // $request = 'bulan-tahun';
        
        if (isset($request->search)) {

            if(isset($request->month) | isset($request->years)){
                $validator = Validator::make($request->all(), [
                    'month' => 'required',
                    'years' => 'required',
                ]);
         
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }

                $month1 = $request->month;
                $year1 = $request->years;

                $date1 = $year1.'-'.$month1.'-01';
                $date2 = $year1.'-'.$month1.'-01';
            }else{
                $validator = Validator::make($request->all(), [
                    'tgl_awal' => 'required',
                    'tgl_akhir' => 'required',
                ]);
         
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
                $date1 = $request->tgl_awal;
                $date2 = $request->tgl_akhir;
            }
            // dd($request);
            $dateawal =date_create($date1)->modify('first day of this month');
            $datdua =date_create($date2)->modify('last day of this month');
    
            $datesatu = $dateawal->format('Y-m-d');//berformat YMD

            $interval = date_diff($dateawal, $datdua)->m + (date_diff($dateawal, $datdua)->y * 12); 
            // looping bulan

            if($interval > 0){
                for($i = 0; $i < $interval; $i ++){
                    $tambah = $i+1;
                    $start[$i] = date('Y-m-01', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                    $end[$i] = date('Y-m-t', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                }
                for ($i=0; $i < $interval; $i++) { 
                    $perbulan[$i] = $start[$i]; 
                    $data[$start[$i]][0] = Homecontroller::getDataAntara($start[$i],$end[$i],0);
                    $data[$start[$i]][1] = Homecontroller::getDataAntara($start[$i],$end[$i],1);
                }
            }else{
                $start = date('Y-m-01',strtotime( $datesatu ));
                $end = date('Y-m-t', strtotime( $datesatu ));
                $data[$start][0] = Homecontroller::getDataAntara($start,$end,0);
                $data[$start][1] = Homecontroller::getDataAntara($start,$end,1);
                $perbulan[0] = $start;//data nama bulan/tahun
            }

            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            $pertanggal = $dateawal->format('Y-m').' to '.$datdua->format('Y-m');//kirim data per tanggal berapa
            return view('export.export', compact('data','listCategory','perbulan','pertanggal'));

            // }//

        }else{
            $now = date("Y-m-d");
            $dateawal =date_create($now)->modify('first day of this month');
            $datdua =date_create($now)->modify('last day of this month');
    
            $datesatu = $dateawal->format('Y-m-d');//berformat YMD

            $interval = date_diff($dateawal, $datdua)->m + (date_diff($dateawal, $datdua)->y * 12); 
            // looping bulan

            if($interval > 0){
                for($i = 0; $i < $interval; $i ++){
                    $tambah = $i+1;
                    $start[$i] = date('Y-m-01', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                    $end[$i] = date('Y-m-t', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                }
                for ($i=0; $i < $interval; $i++) { 
                    $perbulan[$i] = $start[$i]; 
                    $data[$start[$i]][0] = Homecontroller::getDataAntara($start[$i],$end[$i],0);
                    $data[$start[$i]][1] = Homecontroller::getDataAntara($start[$i],$end[$i],1);
                }
            }else{
                $start = date('Y-m-01',strtotime( $datesatu ));
                $end = date('Y-m-t', strtotime( $datesatu ));
                $data[$start][0] = Homecontroller::getDataAntara($start,$end,0);
                $data[$start][1] = Homecontroller::getDataAntara($start,$end,1);
                $perbulan[0] = $start;
            }

            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            $pertanggal = $dateawal->format('Y-m').' to '.$datdua->format('Y-m');
            return view('export.export', compact('data','perbulan','pertanggal','listCategory'));
        }


    }


    public function getDataAntara($start_date,$end_date,$type)
    {
        $data = Transaksi::selectRaw('sum(transaksi.nominal) AS amount , MONTH(transaksi.created_at) As month , YEAR(transaksi.created_at) As Year, categories.nama AS category')
                    ->leftjoin('coa', 'coa.id', '=', 'transaksi.coa_id')
                    ->leftjoin('categories', 'categories.id', '=', 'coa.category_id')
                    ->where('categories.indicator', $type)
                    ->groupBy('categories.nama')
                    ->groupBy(Transaksi::raw('MONTH(transaksi.created_at)'))
                    ->groupBy(Transaksi::raw('YEAR(transaksi.created_at)'))
                    // ->orderBy('coa.category_id')
                    ->whereBetween('transaksi.created_at',[$start_date,$end_date])
                    ->get();               
        return $data;
    }


    public function getDataYear($tahun)
    {

        $debitIndicator = Category::where('indicator', 1)->get();
        $creditIndicator = Category::where('indicator', 0)->get();

        global $debitId;
        global $creditId;
        $i = 0;
        // loop untuk masukin id ke array
        foreach($debitIndicator as $item){
            $debitId[$i] = $item->id;
            $i++;
        }
        $i = 0;
        foreach($creditIndicator as $item){
            $creditId[$i] = $item->id;
            $i++;
        }
        //data debit berdasarkan tahun
        $debitData = Transaksi::whereYear('created_at', $tahun)
                        ->whereHas('coa', function ($query) {
                            global $debitId;
                            return $query->whereIn('category_id', $debitId);
                        })->get();
        //data kredit berdasarkan tahun
        $creditData = Transaksi::whereYear('created_at', $tahun)
                        ->whereHas('coa', function ($query) {
                            global $creditId;
                            return $query->whereIn('category_id', $creditId);
                        })->get();

        $debitSum = $debitData->sum('nominal');
        $creditSum = $creditData->sum('nominal');
        $total = $creditSum - $debitSum;

        $data = array(
            'data' => [
                'debit' => $debitData,
                'credit' => $creditData,
            ],
            'debitsum' => $debitSum,
            'creditsum' => $creditSum,
            'total' => $total,
        );
        return $data;
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
