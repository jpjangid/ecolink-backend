<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FormData;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;

class FormDataController extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            /* Getting all records */
            $active = $request->active == 'all' ? array('1', '0') : array($request->active);
            $allForms = DB::table('form_data');
            if(!empty($request->form_id) && $request->form_id != "all") {
                $allForms = $allForms->where('form_id',$request->form_id);
            }
            $allForms = $allForms->select('id', 'form_id','form_data', 'created_at');
            
            $allForms = $allForms->orderby('created_at','desc')->get();

            /* Converting Selected Data into desired format */
            $forms = new Collection;
            foreach ($allForms as $form) {
                $form_data_collection = collect(json_decode($form->form_data));
                $first_name = $form_data_collection->where('name',"First Name*")->first();
                $first_name = !empty($first_name->value) ? $first_name->value : '';
                $last_name = $form_data_collection->where('name',"Last Name*")->first();
                $last_name = !empty($last_name->value) ? $last_name->value : '';

                $email_address = $form_data_collection->where('name',"Email Address*")->first();
                $email_address = !empty($email_address->value) ? $email_address->value : '';
                
                $mobile_number = $form_data_collection->where('name',"Mobile Number*")->first();
                $mobile_number = !empty($mobile_number->value) ? $mobile_number->value : '';

                $forms->push([
                    'id'        => $form->id,
                    'form_id'   => ucwords(str_replace("_"," ",$form->form_id)),
                    'first_name'=> ucwords($first_name),
                    'last_name'=> ucwords($last_name),
                    'email_address' => $email_address,
                    'mobile_number'=> $mobile_number,
                    'date'      => $form->created_at
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($forms)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/form/delete', $row['id']);
                    $view_url = url('admin/form/detail', $row['id']);
                    $btn = '<div style="display:flex;">
                    <a class="btn btn-primary btn-xs" href="' . $view_url . '" style="margin-right: 2px;"><i class="fas fa-eye"></i></a>
                                   
                                </div>';
                                //  <form action="' . $delete_url . '" method="post">
                                //         <input type="hidden" name="_token" value="' . csrf_token() . '">
                                //         <input type="hidden" name="_method" value="DELETE">
                                //         <button class="delete btn btn-danger btn-xs product_confirm"><i class="fas fa-trash"></i></button>
                                //     </form>
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
           
    }
    
    public function list($form_data_id)
    {
        $form_ids = array("Custom_Blade_Solution",
        "Intern_Onboarding_Form",
        "Intership_Application_Form",
        "Scholarship_Contest_Submission",
        "Scholarship_Winner",
        "cannabis",
        "contact_today");
        return view('form_data.index',compact('form_ids','form_data_id'));
    }

    public function show($id)
    {
        $form_datas = DB::table('form_data')->where('id',$id)->first();
        if($form_datas == null) {
            abort(404);
        }
        return view('form_data.view',compact('form_datas'));
        
    }
}
