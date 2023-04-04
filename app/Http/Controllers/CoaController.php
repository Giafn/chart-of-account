<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use App\Models\Coa;
Use App\Models\Category;
Use App\Models\Transaksi;


class CoaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data =  Coa::select('coa.id','coa.nama','coa.kode','categories.nama AS category','categories.indicator AS indicator')
                    ->join('categories','categories.id','=','coa.category_id')
                    ->get();

        $db_category = Category::get();
        $i=0;
        $row = $db_category->count();

        foreach ($db_category as $items) {
            $category[$i] = array(
                'id' => $items->id,
                'nama' => $items->nama,
                'indicator' => $items->indicator
            );
            $i++;
        }

        return view('master.coa', compact('data','category','row'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            'category_id'   => 'required',
        ]);
        
        global $getType;
        $getType = Category::where('id',$request->category_id)->first()->indicator;
        $kode = Coa::whereHas('category', function($q) {
            global $getType;
            $q->where('indicator', $getType);
        })->get()->max('kode')+1;


        if ($getType == 0 && $kode == 600 && $kode <= 999) {
            $kode = $kode+200;
        } elseif ($getType == 1 && $kode == 800 && $kode <= 1199){
            $kode = $kode+200;
        } elseif ($getType == 0 && $kode > 999 || $getType == 1 && $kode > 1199){
            $kode = Coa::get()->max('kode')+1;
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = new Coa;
        $post->kode = $kode;
        $post->nama = $request->nama;
        $post->category_id = $request->category_id;
        $post->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
        ]);
    }

    public function show($id)
    {
        $category = Coa::where('id', $id)->first();
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Coa',
            'data'    => $category  
        ]); 
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'category_id'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $coaAwal = Coa::where('id', $id)->first();
        $kode = $coaAwal->kode;

        $typeTransaksi = Category::where('id', $coaAwal->category_id)->first()->indicator;

        $type = Category::where('id', $request->category_id)->first()->indicator;
        
        if ($typeTransaksi !== $type) {
            if ($typeTransaksi == 0 && $type == 1) {
                $kode = Coa::whereHas('category', function($q) {
                    $q->where('indicator', '1');
                })->get()->max('kode')+1;
            } elseif ($typeTransaksi == 1 &&  $type == 0) {
                $kode = Coa::whereHas('category', function($q) {
                    $q->where('indicator', '0');
                })->get()->max('kode')+1;
            }
            $refresh = 1;
        }else{
            $refresh = 0;
        }

        $update = new Coa;
        $update->where('id',$id)->update([
            'kode'     => $kode, 
            'nama'     => $request->nama, 
            'category_id'   => $request->category_id
        ]);
        $category_id = Coa::where('id', $id)->first()->category_id;
        $category = Category::where('id', $category_id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diudapte!',
            'refresh' => $refresh,
            'nama' => $request->nama,
            'kode' => $kode,
            'category' => $category->nama,
            'type' => $category->indicator,
            'id'   => $id  
        ]);
    }

    public function destroy($id)
    {
        $coa = Coa::find($id);
        $transaksi = Transaksi::where('coa_id',$id)->get()->count();
        if ($transaksi < 1) {
            $coa->delete();

            return response()->json([
                'success' => true,
                'message' => 'row deleted successfully!.',
            ]); 
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Account is still in use.',
            ]); 
        }
    }

}
