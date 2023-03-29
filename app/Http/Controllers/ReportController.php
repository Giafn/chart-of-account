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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsDataExport;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        // bikin list kategory
        $Category = Category::get()->all();
        $in = 0; $ex = 0;
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
            if($request->search == "bulan"){//kalo pake search bulan dan tahun
                $validator = Validator::make($request->all(), [
                    'month' => 'required',
                    'years' => 'required',
                ]);
         
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }

                // formatting tgl asalnya dari request dua variable jadi satuin menjadi string tanggal
                $date1 = $request->years.'-'.$request->month.'-01';
                $date2 = $request->years.'-'.$request->month.'-01';

            }elseif($request->search == "range"){ //kalo pake search yang range

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

            
            $dateawal =date_create($date1)->modify('first day of this month'); 
            $dateahir =date_create($date2)->modify('last day of this month');
    
            $tanggalformat = $dateawal->format('Y-m');

            // hitung perbedaan bulan dan tahun nanti akan jadi patokan saat looping
            $interval = date_diff($dateawal, $dateahir)->m + (date_diff($dateawal, $dateahir)->y * 12); //perbedaan bulan dan tahun


            if($interval > 0){ //kalo selisih tanggal awal dan ahir nya lebih dari 0 / minimal 1 bulan
                // mengisi variable dengan kelompok per bulan dan per type
                for($i = 0; $i < $interval; $i ++){

                    $start[$i] = date('Y-m-01', strtotime('+'.($i+1).'month', strtotime( $tanggalformat )));
                    $end[$i] = date('Y-m-t', strtotime('+'.($i+1).'month', strtotime( $tanggalformat )));
                
                    $perbulan[$i] = $start[$i]; 
                    $data[$start[$i]][0] = ReportController::getDataAntara($start[$i],$end[$i],0);//type kredit
                    $data[$start[$i]][1] = ReportController::getDataAntara($start[$i],$end[$i],1);//type debit
                }

            }else{ // kalo bulan awal dan ahir selisihnya 0 bulan / hanya satu bulan itu saja

                $start = date('Y-m-01',strtotime( $tanggalformat ));
                $end = date('Y-m-t', strtotime( $tanggalformat ));

                $data[$start][0] = ReportController::getDataAntara($start,$end,0);//type kredit
                $data[$start][1] = ReportController::getDataAntara($start,$end,1);//type debit

                $perbulan[0] = $start;//data nama bulan/tahun
            }

            //kirim data per tanggal berapa
            if($dateawal->format('Y-m') == $dateahir->format('Y-m')){
                $pertanggal = $dateawal->format('Y-m');
            }else{
                $pertanggal = $dateawal->format('Y-m').' to '.$dateahir->format('Y-m');
            }
        
        //kalo gaada filter
        }else{
            $now = date("Y-m-d");

            $start = date('Y-m-01',strtotime( $now ));//awal bulan
            $end = date('Y-m-t', strtotime( $now ));//ahir bulan

            $perbulan[0] = $start;

            $data[$start][0] = ReportController::getDataAntara($start,$end,0);//jenis kredit
            $data[$start][1] = ReportController::getDataAntara($start,$end,1);//jenis Debit

            $pertanggal = date_create($now)->format('Y-m');//nampilin tanggal sekarang
        }


        if(isset($request->export)){ //kalo klik tombol export
            $pass = [
                'data' => $data,
                'perbulan' => $perbulan,
                'listCategory' => $listCategory,
            ];
            return Excel::download(new ReportsDataExport($pass), 'export-'.$pertanggal.'.xlsx');

        }else{
            if(isset($request->search)){
                return view('export.export', compact('data','listCategory','perbulan','pertanggal','request'));
            }else{
                return view('export.export', compact('data','perbulan','pertanggal','listCategory'));
            }
        }


    }


    public function getDataAntara($start_date,$end_date,$type)
    {
        $data = Transaksi::selectRaw('sum(transaksi.nominal) AS amount , MONTH(transaksi.created_at) AS month , YEAR(transaksi.created_at) AS Year, categories.nama AS category')
                    ->leftjoin('coa', 'coa.id', '=', 'transaksi.coa_id')
                    ->leftjoin('categories', 'categories.id', '=', 'coa.category_id')
                    ->where('categories.indicator', $type)
                    ->groupBy('categories.nama')
                    ->groupBy(Transaksi::raw('MONTH(transaksi.created_at)'))
                    ->groupBy(Transaksi::raw('YEAR(transaksi.created_at)'))
                    ->whereBetween('transaksi.created_at',[$start_date,$end_date])
                    ->get();               
        return $data;
    }
}
