<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
Use App\Models\Category;
Use App\Models\Coa;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $data = Category::get();
        return view('master.category', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            'type'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = new Category;
        $post->nama = $request->nama;
        $post->indicator = $request->type;
        $post->save();

        if ($request->type == 0) {
            $type = 'Kredit';
        } else {
            $type = 'Debit';
        }

        $nomor = Category::get()->count();
        $id = $post->id;
        
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

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $update = new Category;
        $update->where('id',$id)->update([
            'nama'     => $request->nama, 
            'indicator'   => $request->type
        ]);

        
        if ($request->type == 0) {
            $type = 'Kredit';
        } else {
            $type = 'Debit';
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diudapte!',
            'nama'    => $request->nama,
            'type'    => $type,
            'nomor'   => $request->nomor,
            'id'   => $id  
        ]);
    }


    public function destroy($id)
    {
        $account = Category::find($id);
        $coa = Coa::where('category_id', $id)->get()->count();
        if ($coa < 1) {
            $account->delete();
            return response()->json([
                'success' => true,
                'message' => 'row deleted successfully!.',
            ]); 
        } else {
            return response()->json([
                'success' => false,
                'message' => 'category data is still in use.',
            ]); 
        }
    }

}
