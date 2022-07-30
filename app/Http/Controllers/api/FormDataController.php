<?php

namespace App\Http\Controllers\api;

use App\Models\FormData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FormDataController extends Controller
{
    public function store(Request $request)
    {
       if(empty($request->form_data) || empty($request->form_id)) {
         return response()->json(['message' => 'Please check form data', 'code' => 400], 400);
       } 

       $fromdata_array = array();
       $formDataArray = json_decode($request->form_data, true);
       foreach($formDataArray as $form_data) {
          $form_data_object = [];
          if($form_data['type'] == "file") {
            $files_array = array();
            if (!empty($request[$form_data['name']]) && $request->hasFile($form_data['name'])) {
                $files = $request->file($form_data['name']);
                foreach($files as $file){
                    $image_name = Str::random(40);
                    $extension = $file->getClientOriginalExtension();
                    $image_name = $image_name.".".$extension;
                    $path = Storage::putFileAs('public/form_data_images/', $file, $image_name);
                    $path = asset('storage/form_data_images/'.$image_name); 
                    array_push($files_array,$path);
                }
            } 
            $form_data_object["name"] = $form_data['name'];
            $form_data_object["type"] = $form_data['type'];
            $form_data_object["value"] = count($files_array) > 0 ? $files_array : '';
          } else {
            $form_data_object["name"] = $form_data['name'];
            $form_data_object["type"] = $form_data['type'];
            $form_data_object["value"] = $form_data['value'];
          }
          array_push($fromdata_array,$form_data_object);
       }

       FormData::create([
        'form_id' => $request->form_id,
        'form_data' => json_encode($fromdata_array)
       ]);
       return response()->json(['message' => 'From submitted successfuly', 'code' => 200], 200);
    }
}
