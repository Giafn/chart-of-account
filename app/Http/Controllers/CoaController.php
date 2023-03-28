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

        foreach($db_category as $items){
            $category[$i] = array(
                'id' => $items->id,
                'nama' => $items->nama,
                'indicator' => $items->indicator
            );

            $i++;
        }

        // dd($data);
        return view('master.coa', compact('data','category','row'));
    }

    public function store(Request $request)
    {
        // untuk kode 
        // type income = 400 - 599, kalo lebih akan di lajut dengan 800 - 999
        // type expanse = 600 -799, kalo lebih akan di lajut dengan 1000 - 1199
        // diatas range yang di sebutkan akan increament bercampur income dan expanse 
        // contoh : expanse 2000, income 2001


        $validator = Validator::make($request->all(), [
            // 'kode'   => 'unique:coa,kode',
            'nama'   => 'required',
            'category_id'   => 'required',
        ]);
        
        global $getType;
        $getType = Category::where('id',$request->category_id)->first()->indicator;
        
        //cari kode paling besar dari tipe nya
        $kode = Coa::whereHas('category', function($q){
            global $getType;
            $q->where('indicator', $getType);
        })->get()->max('kode')+1;

        // return response()->json($kode, 422);

        if($getType == 0 && $kode == 600 && $kode <= 999){
            $kode = $kode+200;
        }elseif($getType == 1 && $kode == 800 && $kode <= 1199){
            $kode = $kode+200;
        }elseif($getType == 0 && $kode > 999 || $getType == 1 && $kode > 1199){
            $kode = Coa::get()->max('kode')+1;
        }

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create post

        $post = new Coa;
        $post->kode = $kode;
        $post->nama = $request->nama;
        $post->category_id = $request->category_id;
        $post->save();



        //return response
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
            // 'kode'     => ['required',Rule::unique('coa')->ignore($id),],
            'nama'     => 'required',
            'category_id'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //ambil data kode dari id
        $coaawal = Coa::where('id', $id)->first();
        $kode = $coaawal->kode;


        // ambil type transaksi awal
        $typeawal = Category::where('id', $coaawal->category_id)->first()->indicator;

        // ambil type transaksi request
        $type = Category::where('id', $request->category_id)->first()->indicator;

        // return response()->json([$type,$typeawal], 422);

        // jika ada perubahan type transaksi maka lakukan perubahan kode
        $refresh = 0;
        if($typeawal !== $type){
            if($typeawal == 0 && $type == 1){
                $kode = Coa::whereHas('category', function($q){
                    $q->where('indicator', '1');
                })->get()->max('kode')+1;


            }elseif($typeawal == 1 &&  $type == 0){
                $kode = Coa::whereHas('category', function($q){
                    $q->where('indicator', '0');
                })->get()->max('kode')+1;
            }
            $refresh = 1;
        }

        //create update
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
        //delete post by ID
        $coa = Coa::find($id);
        $transaksi = Transaksi::where('coa_id',$id)->get()->count();
        //return response 
        //cek transaksi
        if($transaksi < 1){
            $coa->delete();
            //return response
            return response()->json([
                'success' => true,
                'message' => 'row deleted successfully!.',
            ]); 
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Account is still in use.',
            ]); 
        }
    }

}
