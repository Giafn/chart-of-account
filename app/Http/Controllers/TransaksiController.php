<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
Use App\Models\Coa;
Use App\Models\Transaksi;
Use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $fromtanggal = null; //

        $getdata = Transaksi::select('transaksi.*','transaksi.nominal','coa.kode AS kode','coa.nama AS nama_coa','categories.nama AS category','categories.indicator AS indicator')
                            ->join('coa','coa.id','=','transaksi.coa_id')
                            ->join('categories','categories.id','=','coa.category_id')
                            ->orderBy('transaksi.created_at', 'desc');

        if(isset($request->search)){

            $validator = Validator::make($request->all(), [
                'startdate'   => 'required',
                'enddate'   => 'required'
            ]);
    
            //check if validation fails
            if ($validator->fails()) {
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            $start = $request->startdate;
            $end = $request->enddate;

            $data =  $getdata->whereBetween('transaksi.created_at',[$start,$end])//where kalo filter
                    ->get();

            $fromtanggal['start'] = $start;
            $fromtanggal['end'] = $end;
            
        }else{
            $data =  $getdata->get();
        }


    // untuk select category optgroup
        $db_coa = Coa::select('coa.*','categories.nama AS category')
                        ->join('categories','categories.id','=','coa.category_id')
                        ->get();

        $ctgr = Category::get();
        $i=0;
        foreach($ctgr as $items){
            $category[$i] = $items->nama;
            $i++;
        }

        $i=0;
        $row = $db_coa->count();

        foreach($db_coa as $items){
            $coa[$i] = array(
                'id' => $items->id,
                'kode' => $items->kode,
                'nama' => $items->nama,
                'category_id' => $items->category_id,
                'nama_category' => $items->category
            );
            $i++;
        }
    //
        if(isset($request->export)){//kalo request export
            if($fromtanggal != null){
                $range = $fromtanggal['start'].' to '.$fromtanggal['end'];
            }else{
                $range = 'All';
            }
            return Excel::download(new TransaksiExport($data), 'transaksi-'.$range.'.xlsx');
        }else{
            return view('transaksi', compact('data','coa','row','category', 'fromtanggal'));
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coa_id'   => 'required',
            'desc'   => 'required',
            'nominal'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $post = new Transaksi;
        $post->coa_id = $request->coa_id;
        $post->desc = $request->desc;
        $post->nominal = $request->nominal;
        $post->save();



        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
        ]);
    }

    public function show($id)
    {
        $transaksi = Transaksi::where('id', $id)->first();
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Coa',
            'data'    => $transaksi  
        ]); 
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'coa_id'   => 'required',
            'desc'   => 'required',
            'nominal'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create update
        $update = new Transaksi;
        $update->where('id',$id)->update([
            'coa_id'     => $request->coa_id, 
            'desc'     => $request->desc, 
            'nominal'   => $request->nominal
        ]);
        $db = Transaksi::select('transaksi.*','coa.*','coa.nama AS nama_coa','categories.*')
                        ->join('coa','coa.id','=','transaksi.coa_id')
                        ->join('categories','categories.id','=','coa.category_id')
                        ->where('transaksi.id', $id)->first();
        // dd($db);
        $tanggal = date_format($db->created_at,"d/m/Y");
        $kode = $db->kode;
        $category = $db->indicator;

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diudapte!',
            'tanggal' => $tanggal,
            'nama_coa' => $db->nama_coa,
            'nama_category' => $db->nama,
            'kode' => $kode,
            'category' => $category,
            'desc' => $request->desc,
            'nominal' => $request->nominal,
            'id'   => $id  
        ]);
    }

    public function destroy($id)
    {
        //delete post by ID
        Transaksi::where('id', $id)->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'row deleted successfully!.',
        ]); 
    }
}
