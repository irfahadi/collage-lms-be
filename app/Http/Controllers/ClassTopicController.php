<?php

namespace App\Http\Controllers;

use App\Models\ClassTopic;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Http\Controllers\Controller;


//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;


class ClassTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter class_id dari query string
        $classId = $request->query('class_id');

        // Query dasar
        $query = ClassTopic::latest();

        // Jika class_id ada, filter query-nya
        if ($classId) {
            $query->where('class_id', $classId);
        }

        // Ambil data
        $data = $query->get();

         //return collection of posts as a resource
         return new ApiResource(200, 'List Data Topik Kelas', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'class_id' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = ClassTopic::create([
            'title' => $request->title,
            'class_id' => $request->class_id,
        ]);

         //return response
         return new ApiOneResource(200, 'Data Topik Kelas Berhasil Ditambahkan!', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //find post by ID
         $data = ClassTopic::where('id',$id)->with('modules')->first();

         //return single post as a resource
         return new ApiOneResource(200, 'Detail Data Topik Kelas!', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'class_id' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = ClassTopic::where('id',$id);

        //create post
        $data->update([
            'title' => $request->title,
            'class_id' => $request->class_id,
        ]);

         //return response
         return new ApiOneResource(200, 'Data Topik Kelas Berhasil Dirubah!', $data->first());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = ClassTopic::where('id',$id);

        $data->delete();

        return new ApiResource(200, 'Data Topik Kelas Berhasil Dihapus!', ['id' => $id]);
    }
}
