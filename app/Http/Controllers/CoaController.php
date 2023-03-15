<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
Use App\Models\Coa;
Use App\Models\Category;
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
        $data = Coa::with('category')->paginate(10);
        $db_category = Category::get();
        $i=0;
        $row = $db_category->count();
        foreach($db_category as $items){
            $category[$i] = array(
                'id' => $items->id,
                'nama' => $items->nama);
            $i++;
        }

        // dd($category);
        return view('master.coa', compact('data','category','row'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode'   => 'required|unique:coa,kode',
            'nama'   => 'required',
            'category_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $post = new Coa;
        $post->kode = $request->kode;
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
            'kode'     => [
                            'required',
                            Rule::unique('coa')->ignore($id),
                          ],

            'nama'     => 'required',
            'category_id'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create update
        $update = new Coa;
        $update->where('id',$id)->update([
            'kode'     => $request->kode, 
            'nama'     => $request->nama, 
            'category_id'   => $request->category_id
        ]);
        $db = Coa::where('id', $id)->with('category')->first();
        $category = $db->category->nama;

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diudapte!',
            'nama' => $request->nama,
            'kode' => $request->kode,
            'category' => $category,
            'id'   => $id  
        ]);
    }

    public function destroy($id)
    {
        //delete post by ID
        Coa::where('id', $id)->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'row deleted successfully!.',
        ]); 
    }

}
