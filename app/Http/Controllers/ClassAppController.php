<?php

namespace App\Http\Controllers;

use App\Models\ClassApp;
use App\Models\Lecture;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Http\Controllers\Controller;


//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;


class ClassAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter period_id dari query string
        $periodId = $request->query('period_id');

        // Query dasar
        $query = ClassApp::with(['studyProgram','lecturer','period'])->latest();

        // Jika period_id ada, filter query-nya
        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        // Ambil data
        $data = $query->get();

         //return collection of posts as a resource
         return new ApiResource(200, 'List Data Kelas', $data);
    }

    public function lecturers(Request $request)
    {

        // Query dasar
        $query = Lecture::with(['studyProgram'])->latest();

        // Ambil data
        $data = $query->get();

         //return collection of posts as a resource
         return new ApiResource(200, 'List Data Dosen', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'class_code' => 'required', 
            'class_name_long' => 'required', 
            'class_name_short' => 'required', 
            'class_availability' => 'required', 
            // 'visibility' => 'required', 
            'description' => 'required', 
            'tag' => 'required', 
            'responsible_lecturer_id' => 'required', 
            'study_program_id' => 'required', 
            'period_id' => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $data = ClassApp::create([
            'class_code' => $request->class_code, 
            'class_name_long' => $request->class_name_long, 
            'class_name_short' => $request->class_name_short, 
            'class_availability' => $request->class_availability, 
            'visibility' => 1, 
            'description' => $request->description, 
            'class_thumbnail' => $request->class_thumbnail, 
            'tag' => $request->tag, 
            'responsible_lecturer_id' => $request->responsible_lecturer_id, 
            'study_program_id' => $request->study_program_id, 
            'period_id' => $request->period_id
        ]);

         //return response
         return new ApiResource(200, 'Data Kelas Berhasil Ditambahkan!', $data->get());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //find post by ID
         $data = ClassApp::where('id',$id)->with(['studyProgram.faculty','lecturer','period'])->first();

         //return single post as a resource
         return new ApiOneResource(200, 'Detail Data Kelas!', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'class_code' => 'required', 
            'class_name_long' => 'required', 
            'class_name_short' => 'required', 
            'class_availability' => 'required', 
            // 'visibility' => 'required', 
            'description' => 'required', 
            'tag' => 'required', 
            'responsible_lecturer_id' => 'required', 
            'study_program_id' => 'required', 
            'period_id' => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = ClassApp::where('id',$id);

        //create post
        $data->update([
            'class_code' => $request->class_code, 
            'class_name_long' => $request->class_name_long, 
            'class_name_short' => $request->class_name_short, 
            'class_availability' => $request->class_availability, 
            'visibility' => 1, 
            'description' => $request->description, 
            'class_thumbnail' => $request->class_thumbnail, 
            'tag' => $request->tag, 
            'responsible_lecturer_id' => $request->responsible_lecturer_id, 
            'study_program_id' => $request->study_program_id, 
            'period_id' => $request->period_id
        ]);

         //return response
         return new ApiResource(200, 'Data Kelas Berhasil Dirubah!', $data->get());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = ClassApp::where('id',$id);

        $data->delete();

        return new ApiResource(200, 'Data Kelas Berhasil Dihapus!', ['id' => $id]);
    }
}
