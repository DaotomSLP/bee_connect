<?php

namespace App\Http\Controllers;

use App\Models\Import_products;
use App\Models\Lots;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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

        if ($request->date != '') {
            $date = $request->date;
            $date_now = date('Y-m-d', strtotime($request->date));
        } else {
            $date = [Carbon::today()->toDateString()];
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        if (Auth::user()->is_admin != '1') {
            $branch_id = Auth::user()->branch_id;
            $sum_delivery_received = Product::whereDate('created_at', '=', $date)
                ->where('sender_branch_id', $branch_id)
                ->where('status', 'received')
                ->get()->count();

            $sum_delivery_sending = Product::whereDate('created_at', '=', $date)
                ->where('sender_branch_id', $branch_id)
                ->where('status', 'sending')
                ->get()->count();

            $sum_price = Product::whereDate('created_at', '=', $date)
                ->where(function ($query) use ($branch_id) {
                    $query->where('sender_branch_id', $branch_id)
                        ->orWhere('receiver_branch_id', $branch_id);
                })
                ->get()->sum('price');
            // print_r($sum_price);exit;


            $sum_received = Product::whereDate('created_at', '=', $date)
                ->where('receiver_branch_id', $branch_id)
                ->where('status', 'received')
                ->get()->count();

            $sum_success = Product::whereDate('created_at', '=', $date)
                ->where('receiver_branch_id', $branch_id)
                ->where('status', 'success')
                ->get()->count();

            $sum_receive_sending = Product::whereDate('created_at', '=', $date)
                ->where('receiver_branch_id', $branch_id)
                ->where('status', 'sending')
                ->get()->count();


            $branch_money = $sum_price / 5 * 2;
        } else {
            $sum_delivery_received = Product::whereDate('created_at', '=', $date)
                ->where('status', 'received')
                ->get()->count();

            $sum_delivery_sending = Product::whereDate('created_at', '=', $date)
                ->where('status', 'sending')
                ->get()->count();

            $sum_price = Product::whereDate('created_at', '=', $date)
                ->get()->sum('price');

            $sum_received = Product::whereDate('created_at', '=', $date)
                ->where('status', 'received')
                ->get()->count();

            $sum_success = Product::whereDate('created_at', '=', $date)
                ->where('status', 'success')
                ->get()->count();

            $sum_receive_sending = [];

            $branch_money = $sum_price / 5 * 1;
        }

        return view('home', compact('sum_delivery_received', 'sum_delivery_sending', 'sum_receive_sending', 'sum_price', 'branch_money', 'sum_received', 'sum_success', 'date_now'));
    }

    public function dailyImport(Request $request)
    {

        if ($request->date != '') {
            $date = $request->date;
            $date_now = date('Y-m-d', strtotime($request->date));
        } else {
            $date = [Carbon::today()->toDateString()];
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        $branch_id = Auth::user()->branch_id;
        $sum_delivery_received = Import_products::join('lot', 'lot.id', 'import_products.lot_id')
            ->whereDate('import_products.created_at', '=', $date)
            ->where('import_products.status', 'received')
            ->get()->count();

        $sum_delivery_sending = Import_products::join('lot', 'lot.id', 'import_products.lot_id')
            ->whereDate('import_products.created_at', '=', $date)
            ->where('import_products.status', 'sending')
            ->get()->count();

        $sum_price = Lots::whereDate('created_at', '=', $date)
            ->get()->sum('total_price');

        $sum_received = Import_products::join('lot', 'lot.id', 'import_products.lot_id')
            ->whereDate('import_products.created_at', '=', $date)
            ->where('lot.receiver_branch_id', $branch_id)
            ->where('import_products.status', 'received')
            ->get()->count();

        $sum_receive_sending = Import_products::join('lot', 'lot.id', 'import_products.lot_id')
            ->whereDate('import_products.created_at', '=', $date)
            ->where('lot.receiver_branch_id', $branch_id)
            ->where('import_products.status', 'sending')
            ->get()->count();

        return view('dailyimport', compact('sum_delivery_received', 'sum_delivery_sending', 'sum_receive_sending', 'sum_price', 'sum_received', 'date_now'));
    }
}
