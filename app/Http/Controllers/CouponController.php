<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        if (checkpermission('CouponController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $active = $request->active == 'all' ? array('1', '2', '0') : array($request->active);
                $allcoupons = DB::table('coupons')->select('id', 'name', 'code', 'offer_start', 'offer_end', 'created_at', 'show_in_front')->where('flag', '0')->whereIn('show_in_front', $active)->orderby('created_at', 'desc')->get();

                /* Converting Selected Data into desired format */
                $coupons = new Collection;
                foreach ($allcoupons as $coupon) {
                    $coupons->push([
                        'id'            =>  $coupon->id,
                        'name'          =>  $coupon->name,
                        'code'          =>  $coupon->code,
                        // 'type'          =>  $coupon->type,
                        'offer_start'   =>  date('d-m-Y H:i', strtotime($coupon->offer_start)),
                        'offer_end'     =>  date('d-m-Y H:i', strtotime($coupon->offer_end)),
                        // 'days'          =>  $coupon->days,
                        'created_at'    =>  date('d-m-Y H:i', strtotime($coupon->created_at)),
                        'show_in_front' =>  $coupon->show_in_front
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($coupons)
                    ->addIndexColumn()
                    /* Status Active and Deactivated Checkbox */
                    ->addColumn('active', function ($row) {
                        $checked = $row['show_in_front'] == '1' ? 'checked' : '';
                        $active  = '<div class="form-check form-switch form-check-custom form-check-solid" style="padding-left: 3.75rem !important">
                                        <input type="hidden" value="' . $row['id'] . '" class="coupon_id">
                                        <input type="checkbox" class="form-check-input show_in_front  h-25px w-40px" value="' . $row['show_in_front'] . '" ' . $checked . '>
                                    </div>';

                        return $active;
                    })
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/coupons/delete', $row['id']);
                        $edit_url = url('admin/coupons/edit', $row['id']);
                        $btn = '
                        <div style="display:flex;">
                        <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                        <form action="' . $delete_url . '" method="post">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="delete btn btn-danger btn-xs coupan_confirm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }

            return view('coupons.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('CouponController@create')) {
            /* Loading Create Page with users, products and categories data */
            $role = DB::table('roles')->where('name', 'client')->first();
            $users = DB::table('users')->where('role_id', $role->id)->get();
            $products = DB::table('products')->where(['status' => 1, 'flag' => 0])->get();
            $categories = DB::table('categories')->where('parent_id', '=', null)->where(['flag' => '0', 'status' => '1'])->get();
            return view('coupons.create', compact(
                'categories',
                'users',
                'products'
            ));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'name'              =>  'required|unique:coupons,name',
            'code'              =>  'required|unique:coupons,code',
            'show_in_front'     =>  'required',
            'offer_start'       =>  'date',
            'offer_end'         =>  'date|after:offer_start',
            'min_order_amount'  =>  'nullable|numeric|min:1|max:100000',
            'max_order_amount'  =>  'nullable|numeric|min:1|max:100000|gt:min_order_amount'
        ], [
            'name.required'             =>  'Coupan Name is required',
            'code.required'             =>  'Coupon Code is required',
            'show_in_front.required'    =>  'This Field is required',
            'offer_end.after'           =>  'Offer End should be greater than Offer Start',
            'max_order_amount.gt'       =>  'Max Order Amount should be greater than Min Order Amount',
            'min_order_amount.min'      =>  'Min Order Amount should be greater than 0',
            'max_order_amount.min'      =>  'Max Order Amount should be greater than 0',
            'min_order_amount.max'      =>  'Min Order Amount should be less than or equal to 100,000',
            'max_order_amount.max'      =>  'Max Order Amount should be less than or equal to 100,000',
        ]);

        // $days = implode(",", $request->days);

        /* Storing Data in Table */
        Coupon::create([
            'name'                  =>  $request->name,
            'code'                  =>  $request->code,
            'min_order_amount'      =>  $request->min_order_amount,
            'max_order_amount'      =>  $request->max_order_amount,
            'offer_start'           =>  $request->offer_start,
            'offer_end'             =>  $request->offer_end,
            'coupon_limit'          =>  $request->coupon_limit,
            'times_applied'         =>  $request->times_applied,
            'disc_type'             =>  $request->disc_type,
            'discount'              =>  $request->discount,
            'show_in_front'         =>  $request->show_in_front,
            'cat_id'                =>  $request->cat_id,
            'product_id'            =>  $request->product_id,
            'user_id'               =>  $request->user_id,
            'type'                  =>  $request->type,
            // 'days'                  =>  $days,
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/coupons')->with('success', 'Coupon Added Successfully');
    }

    public function edit($id)
    {
        if (checkpermission('CouponController@edit')) {
            /* Getting Coupon data for edit using Id */
            $coupon = DB::table('coupons')->find($id);
            $role = DB::table('roles')->where('name', 'client')->first();
            $users = DB::table('users')->where('role_id', $role->id)->get();
            $products = DB::table('products')->where(['status' => 1, 'flag' => 0])->get();
            $categories = DB::table('categories')->where(['flag' => '0'])->get();

            return view('coupons.edit', compact(
                'categories',
                'users',
                'products',
                'coupon',
                'id'
            ));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'name'              =>  'required|unique:coupons,name,' . $id,
            'code'              =>  'required|unique:coupons,code,' . $id,
            'show_in_front'     =>  'required',
            'offer_start'       =>  'date',
            'offer_end'         =>  'date|after:offer_start',
            'min_order_amount'  =>  'nullable|numeric|min:1|max:100000',
            'max_order_amount'  =>  'nullable|numeric|min:1|max:100000|gt:min_order_amount'
        ], [
            'name.required'             =>  'Coupan Name is required',
            'code.required'             =>  'Coupon Code is required',
            'show_in_front.required'    =>  'This Field is required',
            'offer_end.after'           =>  'Offer End should be greater than Offer Start',
            'max_order_amount.gt'       =>  'Max Order Amount should be greater than Min Order Amount',
            'min_order_amount.min'      =>  'Min Order Amount should be greater than 0',
            'max_order_amount.min'      =>  'Max Order Amount should be greater than 0',
            'min_order_amount.max'      =>  'Min Order Amount should be less than or equal to 100,000',
            'max_order_amount.max'      =>  'Max Order Amount should be less than or equal to 100,000',
        ]);

        /* Fetching Blog Data using Id */
        $coupon = Coupon::find($id);

        // $days = $coupon->days;
        // if (!empty($request->days)) {
        //     $days = implode(",", $request->days);
        // }

        /* Updating Data fetched by Id */
        $coupon->name                   =  $request->name;
        $coupon->code                   =  $request->code;
        $coupon->type                   =  $request->type;
        $coupon->min_order_amount       =  $request->min_order_amount;
        $coupon->max_order_amount       =  $request->max_order_amount;
        $coupon->offer_start            =  $request->offer_start;
        $coupon->offer_end              =  $request->offer_end;
        $coupon->coupon_limit           =  $request->coupon_limit;
        $coupon->times_applied          =  $request->times_applied;
        $coupon->disc_type              =  $request->disc_type;
        $coupon->discount               =  $request->discount;
        $coupon->show_in_front          =  $request->show_in_front;
        $coupon->cat_id                 =  $request->cat_id;
        $coupon->product_id             =  $request->product_id;
        $coupon->user_id                =  $request->user_id;
        /*$coupon->days                   =  $days;*/
        $coupon->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/coupons')->with('success', 'Coupon Updated Successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('CouponController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            Coupon::where('id', $id)->update(['flag' => 1, 'show_in_front' => 0]);

            return redirect('admin/coupons')->with('success', 'Coupon Deleted Successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $coupon = Coupon::find($request->coupon_id);
        $coupon->show_in_front   = $request->show_in_front == 1 ? 0 : 1;
        $coupon->update();
        if ($coupon->show_in_front == 1) {
            $data['msg'] = 'success';
            return response()->json($data);
        } else {
            $data['msg'] = 'danger';
            return response()->json($data);
        }
    }
}
