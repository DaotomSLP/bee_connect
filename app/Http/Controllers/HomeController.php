<?php

namespace App\Http\Controllers;

use App\Models\Expenditure;
use App\Models\Import_products;
use App\Models\Import_products_th;
use App\Models\Lots;
use App\Models\Lots_th;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Product;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {


        $to_date_now = date('Y-m-d', strtotime(Carbon::now()));

        if ($request->date != '') {
            $date =  $request->date;
            $to_date = $request->to_date;
            $date_now = date('Y-m-d', strtotime($request->date));
            $to_date_now = date('Y-m-d', strtotime($request->to_date));
        } else {
            $date = Carbon::today()->toDateString();
            $to_date = Carbon::today()->toDateString();
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        if (Auth::user()->is_admin != '1') {
            $branch_id = Auth::user()->branch_id;
            // echo($branch_id);exit;
            $sum_delivery_received = Product::whereBetween('created_at', [$date, $to_date])
                ->where('sender_branch_id', $branch_id)
                ->where('status', 'received')
                ->get()->count();

            $sum_delivery_sending = Product::whereBetween('created_at', [$date, $to_date])
                ->where('sender_branch_id', $branch_id)
                ->where('status', 'sending')
                ->get()->count();

            $sum_delivery_success = Product::whereBetween('created_at', [$date, $to_date])
                ->where('sender_branch_id', $branch_id)
                ->where('status', 'success')
                ->get()->count();

            $sum_price = Product::whereBetween('created_at', [$date, $to_date])
                ->where(function ($query) use ($branch_id) {
                    $query->where('sender_branch_id', $branch_id)
                        ->orWhere('receiver_branch_id', $branch_id);
                })
                ->get()->sum('price');
            // print_r($sum_price);exit;

            ////////////////////////////
            $sum_received = Product::whereBetween('created_at', [$date, $to_date])
                ->where('receiver_branch_id', $branch_id)
                ->where('status', 'received')
                ->get()->count();

            $sum_success = Product::whereBetween('created_at', [$date, $to_date])
                ->where('receiver_branch_id', $branch_id)
                ->where('status', 'success')
                ->get()->count();

            $sum_receive_sending = Product::whereBetween('created_at', [$date, $to_date])
                ->where('receiver_branch_id', $branch_id)
                ->where('status', 'sending')
                ->get()->count();

            $branch_money = $sum_price / 5 * 2;

            $sum_expenditure = 0;
        } else {
            $sum_delivery_received = 0;

            $sum_delivery_sending = Product::whereBetween('created_at', [$date, $to_date])
                ->where('status', 'sending')
                ->get()->count();

            $sum_price = Product::whereBetween('created_at', [$date, $to_date])
                ->get()->sum('price');

            $sum_received = Product::whereBetween('created_at', [$date, $to_date])
                ->where('status', 'received')
                ->get()->count();

            $sum_success = Product::whereBetween('created_at', [$date, $to_date])
                ->where('status', 'success')
                ->get()->count();

            $sum_receive_sending = [];

            $sum_delivery_success = 0;

            $branch_money = $sum_price / 5 * 1;

            $sum_expenditure = Expenditure::whereBetween('created_at', [$date, $to_date])
                ->sum("price");
        }

        return view('home', compact('sum_delivery_received', 'sum_delivery_sending', 'sum_receive_sending', 'sum_price', 'branch_money', 'sum_received', 'sum_success', 'date_now', 'to_date_now', 'sum_delivery_success', 'sum_expenditure'));
    }

    public function trackingTh()
    {
        return view('trackingTh');
    }


    public function searchTrackingTh(Request $request)
    {
        $results = [];
        $results = Import_products_th::where('code', 'like', '%' . $request->search . '%')->get();

        foreach ($results as $key => $value) {
            $new_results[$key]['code'] = $value->code;
            $new_results[$key]['created_at'] =  $value->created_at ? date_format($value->created_at, 'Y-m-d H:i:s') : null;
            $new_results[$key]['updated_at'] = $value->updated_at ? date_format($value->updated_at, 'Y-m-d H:i:s') : null;
            $new_results[$key]['receive_bc_at'] = $value->receive_bc_at;
            $new_results[$key]['received_at'] = $value->received_at;
            $new_results[$key]['success_at'] = $value->success_at;
        }

        $results = $new_results;

        if ($results) {
            return response()
                ->json($results);
        } else {
            return response()
                ->json(['error' => '1']);
        }
    }
}
