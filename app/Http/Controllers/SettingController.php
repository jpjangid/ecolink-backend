<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit($category_title, $category_des)
    {
    
      $category_title = DB::table('settings')->select('id', 'value')->where('id',$category_title)->first();
      $category_des = DB::table('settings')->select('id', 'value')->where('id',$category_des)->first();
     
        return view('settings.edit',compact('category_title','category_des'));
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category_title, $category_des)
    {
        // dd($request->category_title);
        $request->validate([
            'value1'         =>  'required',
            'value2'        =>  'required'
        ]);
        if(!empty($request->category_title)){
            $settings = Setting::find($category_title);
            $settings->value = $request->value1;
            $settings->update();
        }
      
        // $settings->update([
        //     'value'   =>  $request->value,

        // ]);
        if(!empty($request->category_des)){
            $settings = Setting::find($category_des);
            $settings->value = $request->value2;
            $settings->update();
        }
        // $settings->update([
        //     'value'   =>  $request->value,

        // ]);

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/categories')->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
