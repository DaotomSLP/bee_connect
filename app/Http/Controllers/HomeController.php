<?php

namespace App\Http\Controllers;

use App\Models\Expenditure;
use App\Models\Import_products;
use App\Models\Lots;
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

    public function dailyImport(Request $request)
    {
        $to_date_now = date('Y-m-d', strtotime(Carbon::now()));

        if ($request->date != '') {
            $date = $request->date;
            $to_date = $request->to_date;
            $date_now = date('Y-m-d', strtotime($request->date));
            $to_date_now = date('Y-m-d',  strtotime($request->to_date));
        } else {
            $date = [Carbon::today()->toDateString()];
            $to_date = [Carbon::today()->toDateString()];
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        $branch_id = Auth::user()->branch_id;

        $result = DB::table('lot')
            ->select(DB::raw('branchs.id as receiver_branch_id, branchs.branch_name as branch_name, sum(lot.total_price) as branch_total_price'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name');

        if (Auth::user()->branch_id == null) {

            $sum_base_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->sum("total_base_price");
            $sum_real_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->sum("total_main_price");

            $sum_fee_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->sum("fee");
            $sum_pack_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->sum("pack_price");
        } else {

            $result->where('lot.receiver_branch_id', Auth::user()->branch_id);

            $sum_base_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum("total_main_price");
            $sum_real_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum("total_sale_price");

            $sum_fee_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum("fee");
            $sum_pack_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum("pack_price");
        }

        $sum_sale_profit    = $sum_real_price - $sum_base_price;

        $sum_expenditure = Expenditure::whereBetween('created_at', [$date, $to_date])
            ->sum("price");

        $sum_profit    = $sum_real_price - $sum_base_price - $sum_expenditure;


        $all_branch_sale_totals = $result
            ->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $branch_sale_totals = $result
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' =>  ceil($all_branch_sale_totals / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_branch_sale_totals
        ];

        $import_product_count = DB::table('lot')
            ->select(DB::raw('count(import_products.id) as count_import_product, lot.receiver_branch_id'))
            ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->groupBy('lot.receiver_branch_id')->get();

        $result_unpaid = DB::table('lot')
            ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot.total_main_price) as branch_total_price'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->where('lot.payment_status', 'not_paid')
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get();

        $result_paid = DB::table('lot')
            ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot.total_main_price) as branch_total_price'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->where('lot.payment_status', '<>', 'not_paid')
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get();

        $result_weight = DB::table('lot')
            ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot.weight_kg) as sum_weight_kg'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get();

        $result_weight_m = DB::table('lot')
            ->select(DB::raw('branchs.id as receiver_branch_id, sum(import_products.weight) as sum_weight_m'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->where('import_products.weight_type', 'm')
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get();


        // print_r($result_paid);
        // exit;

        $sum_share = 0;
        if (Auth::user()->is_admin != 1 && Auth::user()->branch_id == null) {
            $sum_share = $sum_profit / Auth::user()->percent;
        }

        return view('dailyimport', compact('sum_base_price', 'sum_real_price', 'sum_sale_profit', 'sum_profit', 'sum_expenditure', 'date_now', 'branch_sale_totals', 'pagination', 'to_date_now', 'import_product_count', 'result_paid', 'result_unpaid', 'sum_fee_price', 'sum_pack_price', 'sum_share', 'result_weight', 'result_weight_m'));
    }
}
