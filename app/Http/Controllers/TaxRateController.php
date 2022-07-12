<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TaxRateController extends Controller
{
    public function index()
    {
        if (checkpermission('TaxRateController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $alltaxrates = DB::table('tax_rates')->select('id', 'country_code', 'state_code', 'city', 'zip', 'rate', 'state_name')->orderby('created_at', 'desc')->get();

                /* Converting Selected Data into desired format */
                $taxrates = new Collection;
                foreach ($alltaxrates as $taxrate) {
                    $taxrates->push([
                        'id'            => $taxrate->id,
                        'country'       => $taxrate->country_code,
                        'state'         => $taxrate->state_name . '(' . $taxrate->state_code . ')',
                        'city'          => $taxrate->city,
                        'zip'           => $taxrate->zip,
                        'rate'          => $taxrate->rate,
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($taxrates)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/taxrates/delete', $row['id']);
                        $edit_url = url('admin/taxrates/edit', $row['id']);
                        $btn = '
                            <div style="display:flex;">
                            <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                            <form action="' . $delete_url . '" method="post">
                                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button class="delete btn btn-danger btn-xs tax_confirm"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('taxrates.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('TaxRateController@create')) {
            /* Loading Create Page */
            return view('taxrates.create');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'country_code'      =>  'required',
            'state_code'        =>  'required',
            'state_name'        =>  'required',
            'city'              =>  'required',
            'zip'               =>  'required',
            'rate'              =>  'required'
        ]);

        /* Storing Data in Table */
        TaxRate::create([
            'country_code'      =>  $request->country_code,
            'state_code'        =>  $request->state_code,
            'state_name'        =>  $request->state_name,
            'city'              =>  $request->city,
            'zip'               =>  $request->zip,
            'rate'              =>  $request->rate,
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/taxrates')->with('success', 'Tax Rate Added successfully');
    }

    public function edit($id)
    {
        if (checkpermission('TaxRateController@edit')) {
            /* Getting Tax Rate data for edit using Id */
            $taxrate = DB::table('tax_rates')->find($id);
            return view('taxrates.edit', compact('id', 'taxrate'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'country_code'      =>  'required',
            'state_code'        =>  'required',
            'state_name'        =>  'required',
            'city'              =>  'required',
            'zip'               =>  'required',
            'rate'              =>  'required'
        ]);

        /* Updating Data fetched by Id */
        $taxrate = TaxRate::find($id);
        $taxrate->country_code  = $request->country_code;
        $taxrate->state_name    = $request->state_name;
        $taxrate->state_code    = $request->state_code;
        $taxrate->city          = $request->city;
        $taxrate->zip           = $request->zip;
        $taxrate->rate          = $request->rate;
        $taxrate->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/taxrates')->with('success', 'Tax Rate Updated successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('TaxRateController@destroy')) {
            /* Delete Tax Rate */
            TaxRate::where('id', $id)->delete();

            return redirect('admin/taxrates')->with('danger', 'Tax Rate Deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
