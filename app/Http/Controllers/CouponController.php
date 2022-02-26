<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CouponController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $allcoupons = DB::table('coupons')->where('flag', '0')->get();

            $coupons = new Collection;
            foreach ($allcoupons as $coupon) {
                $coupons->push([
                    'id'            =>  $coupon->id,
                    'name'          =>  $coupon->name,
                    'code'          =>  $coupon->code,
                    'type'          =>  $coupon->type,
                    'offer_start'   =>  date('d-m-Y H:i', strtotime($coupon->offer_start)),
                    'offer_end'     =>  date('d-m-Y H:i', strtotime($coupon->offer_end)),
                    'days'          =>  $coupon->days,
                    'created_at'    =>  date('d-m-Y H:i', strtotime($coupon->created_at)),
                    'show_in_front' =>  $coupon->show_in_front
                ]);
            }

            return Datatables::of($coupons)
                ->addIndexColumn()
                ->addColumn('active', function ($row) {
                    $checked = $row['show_in_front'] == '1' ? 'checked' : '';
                    $active  = '<div class="form-check form-switch form-check-custom form-check-solid" style="padding-left: 3.75rem !important">
                                        <input type="hidden" value="' . $row['id'] . '" class="coupon_id">
                                        <input type="checkbox" class="form-check-input show_in_front  h-25px w-40px" value="' . $row['show_in_front'] . '" ' . $checked . '>
                                    </div>';

                    return $active;
                })
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/coupons/delete', $row['id']);
                    $edit_url = url('admin/coupons/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }

        return view('coupons.index');
    }

    public function create()
    {
        $users = DB::table('users')->where('role', 'user')->get();
        $products = DB::table('products')->where(['status' => 1, 'flag' => 0])->get();
        $cats = DB::table('categories')->where(['flag' => '0'])->get();

        return view('coupons.create', compact(
            'cats',
            'users',
            'products'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          =>  'required',
            'code'          =>  'required',
            'type'          =>  'required',
        ], [
            'name.required'     =>  'Name is required',
            'code.required'     =>  'Coupon Code is required',
            'type.required'     =>  'Coupon Type is required',
        ]);

        $days = implode(",", $request->days);

        Coupon::create([
            'name'                  =>  $request->name,
            'code'                  =>  $request->code,
            'type'                  =>  $request->type,
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
            'days'                  =>  $days,
        ]);

        return redirect('admin/coupons')->with('success', 'Coupon Added Successfully');
    }

    public function edit($id)
    {
        $coupon = DB::table('coupons')->find($id);
        $users = DB::table('users')->where('role', 'user')->get();
        $products = DB::table('products')->where(['status' => 1, 'flag' => 0])->get();
        $cats = DB::table('categories')->where(['flag' => '0'])->get();

        return view('coupons.edit', compact(
            'cats',
            'users',
            'products',
            'coupon',
            'id'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          =>  'required',
            'code'          =>  'required',
            'type'          =>  'required',
        ], [
            'name.required'     =>  'Name is required',
            'code.required'     =>  'Coupon Code is required',
            'type.required'     =>  'Coupon Type is required',
        ]);

        $coupon = Coupon::find($id);

        $days = $coupon->days;
        if (!empty($request->days)) {
            $days = implode(",", $request->days);
        }

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
        $coupon->days                   =  $days;
        $coupon->update();

        return redirect('admin/coupons')->with('success', 'Coupon Updated Successfully');
    }

    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        $coupon->flag = '1';
        $coupon->update();

        return redirect('admin/coupons')->with('success', 'Coupon Deleted Successfully');
    }

    public function update_status(Request $request)
    {
        $coupon = Coupon::find($request->coupon_id);
        $coupon->show_in_front   = $request->show_in_front == 1 ? 0 : 1;
        $coupon->update();

        return response()->json(['message' => 'Coupon status updated successfully.']);
    }
}
