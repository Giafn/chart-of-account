<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
Use App\Models\Category;
Use App\Models\Coa;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $data = Category::paginate(10);
        return view('master.category', compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            'type'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $post = new Category;
        $post->nama = $request->nama;
        $post->indicator = $request->type;
        $post->save();


        $type = 'Debit';
        if($request->type == 0){
            $type = 'Kredit';
        }

        $nomor = Category::get()->count();
        $id = $post->id;
        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'nama'    => $request->nama,
            'type'    => $type,
            'nomor'   => $nomor,
            'id'      => $id
        ]);
    }

    public function show($id)
    {
        $category = Category::where('id', $id)->first();
        return response()->json([
            'success' => true,
            'message' => 'Detail Data category',
            'data'    => $category  
        ]); 
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'type'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create update
        $update = new Category;
        $update->where('id',$id)->update([
            'nama'     => $request->nama, 
            'indicator'   => $request->type
        ]);

        $type = 'Debit';
        if($request->type == 0){
            $type = 'Kredit';
        }
        $nomor = $request->nomor;

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diudapte!',
            'nama'    => $request->nama,
            'type'    => $type,
            'nomor'   => $nomor,
            'id'   => $id  
        ]);
    }


    public function destroy($id)
    {
        $account = Category::find($id);
        $coa = Coa::where('category_id', $id)->get()->count();
        if($coa < 1){
            $account->delete();
            //return response
            return response()->json([
                'success' => true,
                'message' => 'row deleted successfully!.',
            ]); 
        }else{
            return response()->json([
                'success' => false,
                'message' => 'category data is still in use.',
            ]); 
        }
    }

}
