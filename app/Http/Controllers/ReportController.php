<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use App\Models\Category;
Use App\Models\Transaksi;

use App\Exports\ReportsDataExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $category = Category::get()->all();
        $in = 0; 
        $ex = 0;

        foreach ($category as $key) {
            if($key->indicator == 0){
                $listCategory['income'][$in] = $key->nama;
                $in++;
            }else{
                $listCategory['expense'][$ex] = $key->nama;
                $ex++;
            }
        }

        if (isset($request->search)) {
            if($request->search == "bulan"){
                $validator = Validator::make($request->all(), [
                    'month' => 'required',
                    'years' => 'required',
                ]);
         
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $dateStart = $request->years.'-'.$request->month.'-01';
                $dateEnd = $request->years.'-'.$request->month.'-01';

            }elseif($request->search == "range"){ 

                $validator = Validator::make($request->all(), [
                    'tgl_awal' => 'required',
                    'tgl_akhir' => 'required',
                ]);
         
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $dateStart = $request->tgl_awal;
                $dateEnd = $request->tgl_akhir;
            }

            
            $dateStart =date_create($dateStart)->modify('first day of this month'); 
            $dateEnd =date_create($dateEnd)->modify('last day of this month');
    
            $dateFormat = $dateStart->format('Y-m');

            $interval = date_diff($dateStart, $dateEnd)->m + (date_diff($dateStart, $dateEnd)->y * 12);
            if($interval > 0){ 
                for($i = 0; $i < $interval; $i ++){

                    $start[$i] = date('Y-m-01', strtotime('+'.($i+1).'month', strtotime( $dateFormat )));
                    $end[$i] = date('Y-m-t', strtotime('+'.($i+1).'month', strtotime( $dateFormat )));
                    $perbulan[$i] = $start[$i]; 
                    $data[$start[$i]][0] = ReportController::GetDataAntara($start[$i],$end[$i],0);
                    $data[$start[$i]][1] = ReportController::GetDataAntara($start[$i],$end[$i],1);
                }
            }else{
                $start = date('Y-m-01',strtotime( $dateFormat ));
                $end = date('Y-m-t', strtotime( $dateFormat ));

                $data[$start][0] = ReportController::GetDataAntara($start,$end,0);
                $data[$start][1] = ReportController::GetDataAntara($start,$end,1);
                $perbulan[0] = $start;
            }

            if($dateStart->format('Y-m') == $dateEnd->format('Y-m')){
                $pertanggal = $dateStart->format('Y-m');
            }else{
                $pertanggal = $dateStart->format('Y-m').' to '.$dateEnd->format('Y-m');
            }
        }else{
            $now = date("Y-m-d");
            $start = date('Y-m-01',strtotime( $now ));
            $end = date('Y-m-t', strtotime( $now ));

            $perbulan[0] = $start;
            $data[$start][0] = ReportController::GetDataAntara($start,$end,0);
            $data[$start][1] = ReportController::GetDataAntara($start,$end,1);
            $pertanggal = date_create($now)->format('Y-m');
        }

        if(isset($request->export)){ 
            $pass = [
                'data' => $data,
                'perbulan' => $perbulan,
                'listCategory' => $listCategory,
            ];
            return Excel::download(new ReportsDataExport($pass), 'report-'.$pertanggal.'.xlsx');

        }else{
            if(isset($request->search)){
                return view('export.export', compact('data','listCategory','perbulan','pertanggal','request'));
            }else{
                return view('export.export', compact('data','perbulan','pertanggal','listCategory'));
            }
        }
    }


    public function GetDataAntara($start,$end,$type)
    {
        $data = Transaksi::selectRaw('sum(transaksi.nominal) AS amount , MONTH(transaksi.created_at) AS month , YEAR(transaksi.created_at) AS Year, categories.nama AS category')
                    ->leftjoin('coa', 'coa.id', '=', 'transaksi.coa_id')
                    ->leftjoin('categories', 'categories.id', '=', 'coa.category_id')
                    ->where('categories.indicator', $type)
                    ->groupBy('categories.nama')
                    ->groupBy(Transaksi::raw('MONTH(transaksi.created_at)'))
                    ->groupBy(Transaksi::raw('YEAR(transaksi.created_at)'))
                    ->whereBetween('transaksi.created_at',[$start,$end])
                    ->get();               
        return $data;
    }
}
