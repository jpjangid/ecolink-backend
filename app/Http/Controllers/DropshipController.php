<?php

namespace App\Http\Controllers;

use App\Models\Dropship;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class DropshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->ajax()) {
            /* Getting all records */
            $dropship = Dropship::select('id', 'name', 'country', 'city', 'state', 'zip')->where('status',0)->orderby('created_at', 'desc')->get();

            /* Converting Selected Data into desired format */
            $addresses = new Collection;
            foreach ($dropship as $address) {
                $addresses->push([
                    'id'            =>  $address->id,
                    'name'          =>  $address->name,
                    'country'       =>  $address->country,
                    'city'          =>  $address->city,
                    'state'         =>  $address->state,
                    'zip'           =>  $address->zip,
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($addresses)
                ->addIndexColumn()
                /* Adding Actions like edit, delete and show */
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/dropship/delete', $row['id']);
                    $edit_url = url('admin/dropship/edit', $row['id']);
                    $btn = '
                    <div style="display:flex;">
                    <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                    <form action="' . $delete_url . '" method="post">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="delete btn btn-danger btn-xs dropship"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>';
                    return $btn;
                })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }
        return view('dropship.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dropship.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name'      =>      'required',
            'country'   =>      'required',
            'state'     =>      'required',
            'city'      =>      'required',
            'zip'       =>      'required|max:6'
        ], [
            'name.required'     =>      'Name Field is Required',
            'country.required'  =>      'Country is Required',
            'state.required'    =>      'State is Required',
            'city.required'     =>      'City is Required',
            'zip.required'      =>      'Zip code is Required',
            'zip.max'           =>      'Zip code Limit Should be upto 6 Digits'
        ]);

         Dropship::create([
            'name'        =>        $request->name,
            'country'     =>        $request->country,
            'state'       =>        $request->state,
            'city'        =>        $request->city,
            'zip'         =>        $request->zip
        ]);

        return redirect('admin/dropship')->with('success', 'Dropship Address added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dropship  $dropship
     * @return \Illuminate\Http\Response
     */
    public function show(Dropship $dropship)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dropship  $dropship
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dropship = Dropship::find($id);
        return view('dropship.edit', compact('dropship', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dropship  $dropship
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      =>      'required',
            'country'   =>      'required',
            'state'     =>      'required',
            'city'      =>      'required',
            'zip'       =>      'required|max:6',

        ], [
            'name.required'     =>      'Name Field is Required',
            'country.required'  =>      'Country is Required',
            'state.required'    =>      'State is Required',
            'city.required'     =>      'City is Required',
            'zip.required'      =>      'Zip code is Required',
            'zip.max'           =>      'Zip code Limit Should be upto 6 Digits'
        ]);

        $dropship = Dropship::find($id);
        
            $dropship->name        =      $request->name;
            $dropship->country     =        $request->country;
            $dropship->state       =        $request->state;
            $dropship->city        =        $request->city;
            $dropship->zip         =        $request->zip;
            $dropship->update();

        return redirect('admin/dropship')->with('success', 'Dropship Address added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dropship  $dropship
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dropships = Product::where('dropship', $id)->get();

        if($dropships->isNotEmpty()){
            return redirect('admin/dropship')->with('danger', 'This Value is Being Used Somewhere so Cannot be Deleted.');
        }
        Dropship::where('id', $id)->update(['status'=>1]);
        return redirect('admin/dropship')->with('danger', 'Drop Ship Deleted');
        
    }
}
