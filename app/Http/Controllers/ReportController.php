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

class ReportController extends Controller
{
    public function index(Request $request)
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

        // kalo ada filter
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

            if($interval > 0){
                for($i = 0; $i < $interval; $i ++){
                    $tambah = $i+1;
                    $start[$i] = date('Y-m-01', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                    $end[$i] = date('Y-m-t', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                }
                for ($i=0; $i < $interval; $i++) { 
                    $perbulan[$i] = $start[$i]; 
                    $data[$start[$i]][0] = ReportController::getDataAntara($start[$i],$end[$i],0);
                    $data[$start[$i]][1] = ReportController::getDataAntara($start[$i],$end[$i],1);
                }
            }else{
                $start = date('Y-m-01',strtotime( $datesatu ));
                $end = date('Y-m-t', strtotime( $datesatu ));
                $data[$start][0] = ReportController::getDataAntara($start,$end,0);
                $data[$start][1] = ReportController::getDataAntara($start,$end,1);
                $perbulan[0] = $start;//data nama bulan/tahun
            }

            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;

            //kirim data per tanggal berapa
            if($dateawal->format('Y-m') == $datdua->format('Y-m')){
                $pertanggal = $dateawal->format('Y-m');
            }else{
                $pertanggal = $dateawal->format('Y-m').' to '.$datdua->format('Y-m');
            }
            return view('export.export', compact('data','listCategory','perbulan','pertanggal'));

            // }//

        }else{
            $now = date("Y-m-d");
            $dateawal =date_create($now)->modify('first day of this month');
            $datdua =date_create($now)->modify('last day of this month');
    
            $datesatu = $dateawal->format('Y-m-d');//berformat YMD

            $interval = date_diff($dateawal, $datdua)->m + (date_diff($dateawal, $datdua)->y * 12); 

            if($interval > 0){
                for($i = 0; $i < $interval; $i ++){
                    $tambah = $i+1;
                    $start[$i] = date('Y-m-01', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                    $end[$i] = date('Y-m-t', strtotime('+'.$tambah.'month', strtotime( $datesatu )));
                }
                for ($i=0; $i < $interval; $i++) { 
                    $perbulan[$i] = $start[$i]; 
                    $data[$start[$i]][0] = ReportController::getDataAntara($start[$i],$end[$i],0);
                    $data[$start[$i]][1] = ReportController::getDataAntara($start[$i],$end[$i],1);
                }
            }else{
                $start = date('Y-m-01',strtotime( $datesatu ));
                $end = date('Y-m-t', strtotime( $datesatu ));
                $data[$start][0] = ReportController::getDataAntara($start,$end,0);
                $data[$start][1] = ReportController::getDataAntara($start,$end,1);
                $perbulan[0] = $start;
            }

            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            if($dateawal->format('Y-m') == $datdua->format('Y-m')){
                $pertanggal = $dateawal->format('Y-m');
            }else{
                $pertanggal = $dateawal->format('Y-m').' to '.$datdua->format('Y-m');
            }
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
}
