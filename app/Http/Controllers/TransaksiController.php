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

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return view('transaksi');
        $data = Transaksi::with('coa')->paginate(15);
        $db_coa = Coa::get();
        $i=0;
        $row = $db_coa->count();
        foreach($db_coa as $items){
            $coa[$i] = array(
                'id' => $items->id,
                'kode' => $items->kode,
                'nama' => $items->nama,
                'category_id' => $items->category_id
            );
            $i++;
        }

        // dd($data);
        return view('transaksi', compact('data','coa','row'));
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
        $db = Transaksi::where('id', $id)->with('coa')->first();
        $tanggal = date_format($db->created_at,"d/m/Y");
        $nama = $db->coa->nama;
        $kode = $db->coa->kode;
        $category = $db->coa->category->indicator;

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diudapte!',
            'tanggal' => $tanggal,
            'nama' => $nama,
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
