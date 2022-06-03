<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\Order;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Cart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->role_id == 1) {
            $contacts = ContactUs::where(['type' => 'contact', 'flag' => '0'])->latest()->take(10)->get();
            $askchemists = ContactUs::where(['type' => 'askchemist', 'flag' => '0'])->latest()->take(2)->get();
            $productrequests = ContactUs::where(['type' => 'productrequest', 'flag' => '0'])->latest()->take(2)->get();
            $technicals = ContactUs::where(['type' => 'technical', 'flag' => '0'])->latest()->take(2)->get();
            $bulkpricings = ContactUs::where(['type' => 'bulkpricing', 'flag' => '0'])->latest()->take(2)->get();
            $orders = Order::select('id', 'order_no', 'order_status', 'payment_status', 'total_amount', 'created_at', 'order_comments', 'user_id')->where('flag', '0')->with('user:id,name,mobile', 'items:id,order_id,product_id', 'items.product')->orderby('created_at', 'desc')->latest()->take(15)->get();
            $orderCount = Order::where('flag', '0')->get()->flatten();
            $enquireCount = ContactUs::where('flag', '0')->get()->flatten();
            $productCount = Product::where('flag', '0')->get()->flatten();
            $blogCount = Blog::where('flag', '0')->get()->flatten();
            $CartCount = Cart::get()->flatten();
            return view('home', compact('contacts', 'orders', 'orderCount', 'enquireCount', 'productCount', 'blogCount', 'CartCount', 'askchemists', 'productrequests', 'technicals', 'bulkpricings'));
        }


        // if (Auth::user()->role == "admin") {
        //     $days = array();
        //     $todate = date('Y-m-d', strtotime('- 30 day'));
        //     $fromdate = date('Y-m-d');
        //     $expensearray = array();
        //     $receivablearray = array();
        //     $receiveamt = 0;
        //     $expenseamt = 0;

        //     for ($i = 0; $i < 7; $i++) {
        //         $day = date('d/M', strtotime('-' . $i . 'day'));
        //         array_push($days, $day);
        //     }
        //     $days = array_reverse($days);

        //     for ($i = 0; $i < 7; $i++) {
        //         $date = date('Y-m-d', strtotime('-' . $i . 'day'));
        //         $receives = Receiveable::where([['flag', '=', '0'], ['date', '=', $date]])->get()->flatten();
        //         foreach ($receives as $receive) {
        //             $receiveamt += $receive->amount;
        //         }
        //         array_push($receivablearray, $receiveamt);
        //         $receiveamt = 0;

        //         $expenses = Expense::where([['flag', '=', '0'], ['date', '=', $date]])->get()->flatten();
        //         foreach ($expenses as $expense) {
        //             $expenseamt += $expense->amount;
        //         }
        //         array_push($expensearray, $expenseamt);
        //         $expenseamt = 0;
        //     }
        //     $expensearray = array_reverse($expensearray);
        //     $receivablearray = array_reverse($receivablearray);

        //     $sitecount = Site::where('flag', 0)->count();
        //     $supervisorcount = User::where('role', "supervisor")->count();
        //     $clientcount = User::where('role', "client")->count();
        //     $vendorcount = User::where('role', "vendor")->count();

        //     $date = date('Y-m-d', strtotime('-1 day'));

        //     $purchases = Purchase::where(['flag' => '0', 'status' => 'Pending'])->with('site', 'vendor', 'material.brand', 'unit', 'user')->take(5)->get()->flatten();
        //     $purchaseReturn = PurchaseReturn::where(['flag' => 0, 'status' => 'Pending'])->with('site', 'vendor', 'material.brand', 'unit', 'user')->take(5)->get()->flatten();
        //     $approvedpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date]])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $pendingpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $totalpendingpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Pending']])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $countpurchaseReturn = PurchaseReturn::where(['flag' => 0, 'status' => 'Pending'])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();

        //     $receiveables = Receiveable::where(['flag' => '0', 'status' => 'Pending'])->with('site', 'site.client', 'user')->take(5)->get()->flatten();
        //     $countreceiveables = Receiveable::where(['flag' => '0', 'status' => 'Pending'])->with('site', 'site.client', 'user')->get()->flatten();

        //     $expenses = Expense::where(['flag' => '0', 'status' => 'Pending'])->with('vendor', 'user')->take(5)->get()->flatten();
        //     $pendingexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('vendor', 'user')->get()->flatten();
        //     $totalpendingexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Pending']])->with('vendor', 'user')->get()->flatten();
        //     $approvedexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date]])->with('vendor', 'user')->get()->flatten();

        //     return view('admin.home', compact([
        //         'sitecount', 'supervisorcount', 'clientcount', 'vendorcount', 'purchases', 'purchaseReturn',
        //         'receiveables', 'expenses', 'approvedpurchases', 'pendingpurchases', 'countpurchaseReturn', 'countreceiveables', 'pendingexpenses',
        //         'approvedexpenses', 'receivablearray', 'expensearray', 'days', 'totalpendingpurchases', 'totalpendingexpenses'
        //     ]));
        // } elseif (Auth::user()->role == "supervisor") {
        //     $date = date('Y-m-d', strtotime('-1 day'));
        //     $tasks = SiteProgressDetail::where(['supervisor_id' => Auth::user()->id, 'status' => '0'])->with('task', 'subtask', 'progress', 'site')->get();
        //     $sitecount = SiteSupervisor::where('supervisor_id', Auth::user()->id)->count();
        //     $approvedpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date]])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $pendingpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $pendingexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('vendor', 'user')->get()->flatten();
        //     $approvedexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date]])->with('vendor', 'user')->get()->flatten();
        //     $days = array();
        //     $expensearray = array();
        //     $receivablearray = array();
        //     return view('admin.home', compact([
        //         'sitecount', 'tasks', 'approvedpurchases', 'pendingpurchases',
        //         'pendingexpenses', 'approvedexpenses', 'days', 'expensearray', 'receivablearray'
        //     ]));
        // } elseif (Auth::user()->role == "client") {
        //     $date = date('Y-m-d', strtotime('-1 day'));
        //     $approvedpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date]])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $pendingpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $pendingexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('vendor', 'user')->get()->flatten();
        //     $approvedexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date]])->with('vendor', 'user')->get()->flatten();
        //     $days = array();
        //     $expensearray = array();
        //     $receivablearray = array();
        //     return view('admin.home', compact([
        //         'approvedpurchases', 'pendingpurchases', 'pendingexpenses', 'approvedexpenses',
        //         'days', 'expensearray', 'receivablearray'
        //     ]));
        // } elseif (Auth::user()->role == "vendor") {
        //     $date = date('Y-m-d', strtotime('-1 day'));
        //     $approvedpurchases = Purchase::where(['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $pendingpurchases = Purchase::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('site', 'vendor', 'material.brand', 'unit', 'user')->get()->flatten();
        //     $pendingexpenses = Expense::where([['flag', '=', '0'], ['status', '=', 'Pending'], ['date', '>', $date]])->with('vendor', 'user')->get()->flatten();
        //     $approvedexpenses = Expense::where(['flag', '=', '0'], ['status', '=', 'Approved'], ['date', '>', $date])->with('vendor', 'user')->get()->flatten();
        //     $days = array();
        //     $expensearray = array();
        //     $receivablearray = array();
        //     return view('admin.home', compact([
        //         'approvedpurchases', 'pendingpurchases', 'pendingexpenses', 'approvedexpenses',
        //         'days', 'expensearray', 'receivablearray'
        //     ]));
        // }
    }
}
