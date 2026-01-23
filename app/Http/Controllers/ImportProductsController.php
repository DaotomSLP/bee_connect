<?php

namespace App\Http\Controllers;

use App\Models\Bills;
use App\Models\Branchs;
use App\Models\Delivery_rounds;
use App\Models\Districts;
use App\Models\Expenditure;
use App\Models\Import_products;
use App\Models\IncomeCh;
use App\Models\Lost_products;
use App\Models\Lots;
use App\Models\Price_imports;
use App\Models\Provinces;
use App\Models\Receipt_images;
use App\Models\Service_charge;
use App\Models\User;
use App\Models\WithdrawCh;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use function PHPSTORM_META\type;
use GImage\Image;

class ImportProductsController extends Controller
{
    public function index(Request $request)
    {
        $provinces = Provinces::all();
        $districts = Districts::all();
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();
        $delivery_rounds = Delivery_rounds::orderBy('id', 'desc')->get();

        // $lots = Lots::all();

        // foreach ($lots as $key => $value) {
        //   $new_lot = ['total_main_price' => $value->total_price];
        //   Lots::where('id', $value->id)->update($new_lot);
        // }

        if (Auth::user()->is_admin == 1) {
            return view('import', compact('provinces', 'districts', 'branchs', 'delivery_rounds'));
        } else {
            return view('importForUser', compact('provinces', 'districts', 'branchs'));
        }
    }

    public function lostProduct()
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        return view('lostProduct');
    }

    public function insertLostProduct(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        if ($request->item_id) {
            $count = 0;
            foreach ($request->item_id as $product_id) {
                $product = new Lost_products();
                $product->code = $product_id;
                $product->weight = $request->weight[$count];
                $product->status = 'receive';

                if ($product->save()) {
                }

                $count++;
            }

            return redirect('lostProduct')->with(['error' => 'insert_success']);
        } else {
            return redirect('lostProduct')->with(['error' => 'not_insert']);
        }
    }

    public function lostProductLists(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $status = $request->status;

        $result = Lost_products::query();

        $result->select('lost_products.*');


        $to_date_now = date('Y-m-d', strtotime(Carbon::now()));

        if ($request->date != '') {
            $date = $request->date;
            $to_date = $request->to_date;
            $date_now = date('Y-m-d', strtotime($request->date));
            $to_date_now = date('Y-m-d', strtotime($request->to_date));
        } else {
            $date = [Carbon::today()->toDateString()];
            $to_date = [Carbon::today()->toDateString()];
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        $result->whereBetween('lost_products.created_at', [$date, $to_date]);

        if ($request->product_id != '') {
            $result->where('lost_products.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('lost_products.status', $request->status);
        }

        $all_lost_products = $result->orderBy('lost_products.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $lost_products = $result
            ->orderBy('lost_products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_lost_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_lost_products,
        ];

        return view('lostProductLists', compact('lost_products', 'pagination', 'status', 'date_now', 'to_date_now'));
    }

    public function printLostProductLists(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $result = Lost_products::query();

        $result->select('lost_products.*');

        if ($request->date != '') {
            $date = $request->date;
            $to_date = $request->to_date;
        } else {
            $date = [Carbon::today()->toDateString()];
            $to_date = [Carbon::today()->toDateString()];
        }

        $result->whereBetween('lost_products.created_at', [$date, $to_date]);

        if ($request->send_date != '') {
            $result->whereDate('lost_products.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('lost_products.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('lost_products.status', $request->status);
        }

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $lost_products = $result
            ->orderBy('lost_products.id', 'desc')
            ->get();


        $data = [
            'lost_products' => $lost_products,
        ];

        $pdf = PDF::loadView(
            'pdf.lostProductListsPrint',
            $data,
            [],
            [
                'format' => 'A4',
                // 'orientation' => 'landscape',
                'custom_font_dir' => base_path('resources/fonts/'),
                'custom_font_data' => [
                    'defago' => [ // must be lowercase and snake_case
                        'R'  => 'defago-noto-sans-lao.ttf',    // regular font
                        'B'  => 'DefagoNotoSansLaoBold.ttf',    // bold font
                    ]
                    // ...add as many as you want.
                ]
            ]
        );

        return $pdf->stream('document.pdf');
    }

    public function sendLostProduct(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        Lost_products::where('id', $request->id)->update([
            'status' => 'success',
        ]);

        return redirect()
            ->back()
            ->with(['error' => 'insert_success']);
    }

    public function dailyImport(Request $request)
    {
        // $all = Lots::all();
        // // print_r($all);
        // // exit;

        // foreach ($all as $key => $value) {
        //   $sum = Import_products::where("lot_id", $value->id)->where("weight_type", "m")->sum("weight");
        //   Lots::where('id', $value->id)->update(['weight_m' => $sum]);
        // }

        if (Auth::user()->is_thai_admin == 1) {
            return redirect('/addImportTh');
        }

        if (Auth::user()->is_thai_admin_in_lao == 1) {
            return redirect('/importTh');
        }

        $to_date_now = date('Y-m-d', strtotime(Carbon::now()));

        if ($request->date != '') {
            $date = $request->date;
            $to_date = $request->to_date;
            $date_now = date('Y-m-d', strtotime($request->date));
            $to_date_now = date('Y-m-d', strtotime($request->to_date));
        } else {
            $date = [Carbon::today()->toDateString()];
            $to_date = [Carbon::today()->toDateString()];
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        $branch_id = Auth::user()->branch_id;

        $result = DB::table('lot')
            ->select(DB::raw('branchs.id as receiver_branch_id, branchs.branch_name as branch_name, sum(lot.total_main_price) as branch_total_price, sum(lot.weight_kg) as weight_kg, sum(lot.weight_m) as weight_m'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name');

        if (Auth::user()->branch_id != null) {
            $result->where('lot.receiver_branch_id', Auth::user()->branch_id);

            $sum_base_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum('total_main_price');
            $sum_real_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum('total_sale_price');

            $sum_weight_kg_branch = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum('weight_kg');

            $sum_weight_m_branch = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum('weight_m');

            $sum_fee_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum('fee');
            $sum_pack_price = Lots::whereBetween('lot.created_at', [$date, $to_date])
                ->where('lot.receiver_branch_id', Auth::user()->branch_id)
                ->sum('pack_price');

            $sum_sale_profit = $sum_real_price - $sum_base_price;

            //unused:
            $sum_expenditure = 0;
            $sum_profit = 0;
            $sum_real_price_paid = 0;
            $sum_real_price_unpaid = 0;
        } else {
            //admin
            $sum_weight_kg_branch = 0;
            $sum_weight_m_branch = 0;
            $sum_real_price = Lots::whereBetween('lot.created_at', [$date, $to_date])->sum('total_main_price');

            $sum_real_price_paid = Lots::whereBetween('lot.created_at', [$date, $to_date])->where('lot.payment_status', '<>', 'not_paid')->sum('total_main_price');
            $sum_real_price_unpaid = Lots::whereBetween('lot.created_at', [$date, $to_date])->where('lot.payment_status', 'not_paid')->sum('total_main_price');

            $sum_fee_price = Lots::whereBetween('lot.created_at', [$date, $to_date])->sum('fee');
            $sum_pack_price = Lots::whereBetween('lot.created_at', [$date, $to_date])->sum('pack_price');

            $sum_expenditure = Expenditure::whereBetween('created_at', [$date, $to_date])->sum('price');

            $sum_profit = $sum_real_price_paid - $sum_expenditure;

            //unused:
            $sum_base_price = 0;
            $sum_sale_profit = 0;
        }

        $all_branch_sale_totals = $result->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $branch_sale_totals = $result->limit(25)->get();
        $branch_sale_totals_branch_name = $result
            ->limit(25)
            ->get()
            ->pluck('branch_name');

        $branch_sale_totals_kg = $result
            ->limit(25)
            ->get()
            ->pluck('weight_kg');

        $branch_sale_totals_m = $result
            ->limit(25)
            ->get()
            ->pluck('weight_m');

        $branch_sale_totals_price = $result
            ->limit(25)
            ->get()
            ->pluck('branch_total_price');

        $branch_sale_totals_paid = DB::table('lot')
            ->select(DB::raw('sum(lot.total_main_price) as branch_total_price'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->where('lot.payment_status', '<>', 'not_paid')
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get()
            ->pluck('branch_total_price');

        $branch_sale_totals_unpaid = DB::table('lot')
            ->select(DB::raw('sum(lot.total_main_price) as branch_total_price'))
            // ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->where('lot.payment_status', 'not_paid')
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get()
            ->pluck('branch_total_price');

        $pagination = [
            'offsets' => ceil($all_branch_sale_totals / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_branch_sale_totals,
        ];

        $import_product_count = DB::table('lot')
            ->select(DB::raw('count(import_products.id) as count_import_product, lot.receiver_branch_id'))
            ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->groupBy('lot.receiver_branch_id')
            ->get();

        $import_product_count_for_chart = DB::table('lot')
            ->select(DB::raw('count(import_products.id) as count_import_product'))
            ->join('import_products', 'lot.id', 'import_products.lot_id')
            ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot.created_at', [$date, $to_date])
            ->groupBy('lot.receiver_branch_id')
            ->get()
            ->pluck('count_import_product');

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

        // print_r($import_product_count);
        // exit;

        $sum_share = 0;
        if (Auth::user()->is_ch_partner == 1) {
            $sum_share = $sum_profit * (Auth::user()->ch_percent / 100);
        }

        return view('dailyimport', compact('sum_base_price', 'sum_real_price', 'sum_real_price_paid', 'sum_real_price_unpaid', 'sum_sale_profit', 'sum_profit', 'sum_expenditure', 'date_now', 'branch_sale_totals', 'pagination', 'to_date_now', 'import_product_count', 'result_paid', 'result_unpaid', 'sum_fee_price', 'sum_pack_price', 'sum_share', 'result_weight', 'result_weight_m', 'sum_weight_kg_branch', 'sum_weight_m_branch', 'branch_sale_totals_branch_name', 'branch_sale_totals_kg', 'branch_sale_totals_m', 'import_product_count_for_chart', 'branch_sale_totals_price', 'branch_sale_totals_unpaid', 'branch_sale_totals_paid'));
    }

    public function insertImport(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        if ($request->item_id) {
            $sum_price = 0;
            $sum_m_weight = 0;
            $sum_kg_weight = 0;

            if ($request->parcel_size == 'normal') {
                if ($request->weight_kg <= 0) {
                    return redirect('import')->with(['error' => 'not_insert']);
                }

                $sum_kg_weight = $request->weight_kg;
            } else {
                $count = 0;
                foreach ($request->weight_type as $weight_type) {
                    if ($weight_type == 'kg') {
                        $sum_kg_weight += $request->weight[$count];
                    }

                    $count++;
                }
            }

            $count = 0;
            foreach ($request->weight_type as $weight_type) {
                if ($weight_type == 'm') {
                    $sum_m_weight += $request->weight[$count];
                }

                $count++;
            }

            $sum_service_charge = 0;
            if (isset($request->service_item_price)) {
                foreach ($request->service_item_price as $key => $price) {
                    $sum_service_charge += $price;
                }
            }

            $default_price_kg = Price_imports::where('weight_type', 'kg')
                ->orderBy('id', 'DESC')
                ->first();

            $default_price_m = Price_imports::where('weight_type', 'm')
                ->orderBy('id', 'DESC')
                ->first();

            $sum_kg_base_price = ($request->base_price_kg == '' ? $default_price_kg->base_price : $request->base_price_kg) * $sum_kg_weight;
            $sum_m_base_price = ($request->base_price_m == '' ? $default_price_m->base_price : $request->base_price_m) * $sum_m_weight;
            $sum_base_price = $sum_m_base_price + $sum_kg_base_price;

            $sum_kg_price = ($request->real_price_kg == '' ? $default_price_kg->real_price : $request->real_price_kg) * $sum_kg_weight;
            $sum_m_price = ($request->real_price_m == '' ? $default_price_m->real_price : $request->real_price_m) * $sum_m_weight;
            $sum_price = $sum_m_price + $sum_kg_price;

            $lot = new Lots();
            $lot->receiver_branch_id = $request->receiver_branch_id;
            $lot->weight_kg = $sum_kg_weight;
            $lot->total_base_price_kg = $sum_kg_base_price;
            $lot->total_base_price_m = $sum_m_base_price;
            $lot->total_base_price = $sum_base_price;
            $lot->total_main_price = $sum_price + $request->fee + $request->pack_price + $sum_service_charge;
            $lot->total_price = $sum_price;
            $lot->total_unit_m = $sum_m_price;
            $lot->total_unit_kg = $sum_kg_price;
            $lot->status = 'sending';
            $lot->payment_status = 'not_paid';
            $lot->fee = $request->fee;
            $lot->pack_price = $request->pack_price;
            $lot->lot_real_price_kg = ($request->real_price_kg == '' ? $default_price_kg->real_price : $request->real_price_kg);
            $lot->lot_base_price_kg = ($request->base_price_kg == '' ? $default_price_kg->base_price : $request->base_price_kg);
            $lot->lot_real_price_m = ($request->real_price_m == '' ? $default_price_m->real_price : $request->real_price_m);
            $lot->lot_base_price_m = ($request->real_price_m == '' ? $default_price_m->real_price : $request->real_price_m);
            $lot->service_charge = $sum_service_charge;
            $lot->weight_m = $sum_m_weight;
            $lot->money_rate = $request->money_rate;
            $lot->real_price_m_yuan = $request->real_price_m_yuan;
            $lot->parcel_size = $request->parcel_size;
            $lot->delivery_round_id = $request->delivery_round_id;

            if ($lot->save()) {
                $count = 0;
                foreach ($request->item_id as $product_id) {
                    $price = Price_imports::where('weight_type', $request->weight_type[$count])
                        ->orderBy('id', 'DESC')
                        ->first();

                    $product = new Import_products();
                    $product->code = $product_id;

                    if ($request->weight_type[$count] == 'm') {
                        $product->weight = $request->weight[$count];
                        $product->base_price = $request->base_price_m == '' ? $price->base_price : $request->base_price_m;
                        $product->real_price = $request->real_price_m == '' ? $price->real_price : $request->real_price_m;
                        // $product->total_base_price = ($request->base_price_m == '' ? $price->base_price : $request->base_price_m) * $request->weight[$count];
                        // $product->total_real_price = ($request->real_price_m == '' ? $price->real_price : $request->real_price_m) * $request->weight[$count];
                        // $product->total_sale_price = 0;
                    } else {
                        $product_weight = $request->parcel_size == 'large' ? $request->weight[$count] : 0;
                        $product->weight = $product_weight;
                        $product->base_price = $request->base_price_kg == '' ? $price->base_price : $request->base_price_kg;
                        $product->real_price = $request->real_price_kg == '' ? $price->real_price : $request->real_price_kg;
                        // $product->total_base_price = ($request->base_price_kg == '' ? $price->base_price : $request->base_price_kg) * $product_weight;
                        // $product->total_real_price = ($request->real_price_kg == '' ? $price->real_price : $request->real_price_kg) * $product_weight;
                        // $product->total_sale_price = 0;
                    }

                    $product->weight_type = $request->weight_type[$count];
                    $product->status = 'sending';
                    $product->lot_id = $lot->id;

                    if ($product->save()) {
                    }

                    $count++;
                }

                if (isset($request->service_item_price)) {
                    foreach ($request->service_item_price as $key => $price) {
                        $service_charge = new Service_charge();
                        $service_charge->name = $request->service_item_name[$key];
                        $service_charge->price = $price;
                        $service_charge->lot_id = $lot->id;
                        $service_charge->save();
                    }
                }

                return redirect('import')->with(['error' => 'insert_success', 'id' => $lot->id]);
            } else {
                return redirect('import')->with(['error' => 'not_insert']);
            }
        } else {
            return redirect('import')->with(['error' => 'not_insert']);
        }
    }

    public function insertImportForUser(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        if ($request->item_id) {
            $count = 0;
            foreach ($request->item_id as $product_code) {
                $import_product = Import_products::where('id', $product_code)
                    ->orderBy('id', 'DESC')
                    ->first();

                $new_import_product_update = [
                    'received_at' => Carbon::now(),
                    'status' => 'received',
                ];

                if (Import_products::where('id', $import_product->id)->update($new_import_product_update)) {
                    $count_status = Import_products::where('status', 'sending')
                        ->where('lot_id', $import_product->lot_id)
                        ->count();
                    if ($count_status < 1) {
                        Lots::where('id', $import_product->lot_id)->update(['status' => 'received']);
                    } else {
                        Lots::where('id', $import_product->lot_id)->update(['status' => 'not_full']);
                    }
                }

                $count++;
            }

            return redirect('import')->with(['error' => 'insert_success']);
        } else {
            return redirect('import')->with(['error' => 'not_insert']);
        }
    }

    public function importView(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        // $prods = Import_products::distinct()->get('lot_id');
        // foreach ($prods as $key => $value) {
        //   $prod_m = Import_products::where('weight_type', 'm')->where('lot_id', $value->lot_id)
        //     ->first();

        //   if ($prod_m) {
        //     Lots::where('id', $value->lot_id)->update([
        //       'lot_base_price_m' => $prod_m->base_price,
        //       'lot_real_price_m' => $prod_m->real_price,
        //     ]);
        //   }

        //   $prod_kg = Import_products::where('weight_type', 'kg')->where('lot_id', $value->lot_id)
        //     ->first();

        //   if ($prod_kg) {
        //     Lots::where('id', $value->lot_id)->update([
        //       'lot_base_price_kg' => $prod_kg->base_price,
        //       'lot_real_price_kg' => $prod_kg->real_price,
        //     ]);
        //   }
        // }

        $delivery_rounds = Delivery_rounds::orderBy('id', 'desc')->get();

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();
        $result = Lots::query();

        $result->select(
            'lot.*',
            'receive.branch_name as receiver_branch_name',
            'delivery_rounds.round',
            'delivery_rounds.month',
            'delivery_rounds.departure_time'
        )
            ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
            ->leftJoin('delivery_rounds', 'lot.delivery_round_id', 'delivery_rounds.id');

        // if (Auth::user()->is_admin != '1') {
        //   $result->where('import_products.sender_branch_id', Auth::user()->branch_id);
        // }

        if ($request->send_date != '') {
            $result->whereDate('lot.created_at', '=', $request->send_date);
        }
        if ($request->id != '') {
            $result->where('lot.id', $request->id);
        }
        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->payment_status != '') {
            $result->where('payment_status', $request->payment_status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        if ($request->delivery_round_id != '') {
            $result->where('delivery_round_id', $request->delivery_round_id);
        }

        $all_lots = $result->orderBy('lot.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $lots = $result
            ->orderBy('lot.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_lots / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_lots,
        ];

        return view('importView', compact('branchs', 'delivery_rounds', 'lots', 'pagination'));
    }

    public function importViewForUser(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();
        $result = Lots::query();

        $result
            ->select(
                'lot.*',
                'receive.branch_name as receiver_branch_name',
            )
            ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
            ->where('receiver_branch_id', Auth::user()->branch_id);

        // if (Auth::user()->is_admin != '1') {
        //   $result->where('import_products.sender_branch_id', Auth::user()->branch_id);
        // }

        if ($request->send_date != '') {
            $result->whereDate('lot.created_at', '=', $request->send_date);
        }
        if ($request->id != '') {
            $result->where('lot.id', $request->id);
        }
        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_lots = $result->orderBy('lot.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $lots = $result
            ->orderBy('lot.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_lots / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_lots,
        ];

        return view('importView', compact('branchs', 'lots', 'pagination'));
    }

    // public function report($id)
    // {
    //     // Set higher limits for large reports
    //     ini_set('max_execution_time', '300');
    //     ini_set('memory_limit', '1024M');

    //     $lot = DB::table('lot')
    //         ->select('lot.*', 'branchs.branch_name')
    //         ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
    //         ->where('lot.id', $id)
    //         ->first();

    //     $service_charges = Service_charge::where('lot_id', $id)->get();

    //     ini_set("pcre.backtrack_limit", "5000000");
    //     ini_set("pcre.recursion_limit", "5000000");

    //     if ($lot->parcel_size === 'large') {
    //         // --- 5. Prepare Data for View ---

    //         $import_products = DB::table('import_products')
    //             ->select('import_products.*', 'branchs.branch_name')
    //             ->join('lot', 'lot.id', 'import_products.lot_id')
    //             ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
    //             ->where('lot.id', $id)
    //             ->orderBy('import_products.id', 'desc')
    //             ->get();

    //         $data = [
    //             'lot' => $lot,
    //             'service_charges' => $service_charges,
    //             'import_products' => $import_products
    //         ];

    //         $pdf = PDF::loadView(
    //             'pdf.import',
    //             $data,
    //             [],
    //             [
    //                 'format' => 'A4',
    //                 'orientation' => 'landscape',
    //                 'custom_font_dir' => base_path('resources/fonts/'),
    //                 'custom_font_data' => [
    //                     'defago' => [ // must be lowercase and snake_case
    //                         'R'  => 'defago-noto-sans-lao.ttf',    // regular font
    //                         'B'  => 'DefagoNotoSansLaoBold.ttf',    // bold font
    //                     ]
    //                     // ...add as many as you want.
    //                 ]
    //             ]
    //         );

    //         return $pdf->stream('document.pdf');
    //     } else {
    //         $data = [
    //             'lot' => $lot,
    //             'service_charges' => $service_charges,
    //         ];

    //         $pdf = PDF::loadView(
    //             'pdf.importKg',
    //             $data,
    //             [],
    //             [
    //                 'format' => 'A4',
    //                 'orientation' => 'landscape',
    //                 'custom_font_dir' => base_path('resources/fonts/'),
    //                 'custom_font_data' => [
    //                     'defago' => [ // must be lowercase and snake_case
    //                         'R'  => 'defago-noto-sans-lao.ttf',    // regular font
    //                         'B'  => 'DefagoNotoSansLaoBold.ttf',    // bold font
    //                     ]
    //                     // ...add as many as you want.
    //                 ]
    //             ]
    //         );

    //         return $pdf->stream('document.pdf');
    //     }
    // }

    public function importDetail(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $lot = Lots::join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
            ->where('lot.id', $request->id)
            ->first();

        $result = Import_products::query();

        $result->select('import_products.*')->where('lot_id', $request->id);

        if ($request->send_date != '') {
            $result->whereDate('import_products.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        // dd ($lot);
        // exit;

        return view('importDetail', compact('branchs', 'import_products', 'pagination', 'lot'));
    }

    public function importDetailForUser(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $result = Import_products::query();

        $result
            ->select('import_products.*')
            ->join('lot', 'lot.id', 'import_products.lot_id')
            ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
            ->where('lot_id', $request->id)
            ->where('lot.receiver_branch_id', Auth::user()->branch_id);

        if ($request->send_date != '') {
            $result->whereDate('import_products.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('import_products.status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('importDetailForUser', compact('branchs', 'import_products', 'pagination'));
    }

    public function importProductTrack(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $result = Import_products::query();

        $result
            ->select('import_products.*', 'receive.branch_name')
            ->join('lot', 'lot.id', 'import_products.lot_id')
            ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id');

        if ($request->send_date != '') {
            $result->whereDate('import_products.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('import_products.status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('allImportDetail', compact('branchs', 'import_products', 'pagination'));
    }

    public function serviceChargeDetail(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $service_charges = Service_charge::where('lot_id', $request->id)->get();
        return view('serviceChargeDetail', compact('service_charges'));
    }

    public function editServiceCharge(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        if (isset($request->service_item_price)) {
            $sum = 0;
            foreach ($request->service_item_price as $key => $price) {
                $sum += $price;
                Service_charge::where('id', $request->service_item_id[$key])->update([
                    'name' => $request->service_item_name[$key],
                    'price' => $price,
                ]);
            }
            Lots::where('id', $request->lot_id)->update(['service_charge' => $sum]);
            return redirect('serviceChargeDetail?id=' . $request->lot_id)->with(['error' => 'insert_success']);
        }
    }

    public function importProductTrackForUser(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('branchs.enabled', '1')->get();

        $result = Import_products::query();

        $result
            ->select('import_products.*', 'receive.branch_name')
            ->join('lot', 'lot.id', 'import_products.lot_id')
            ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id');

        if ($request->send_date != '') {
            $result->whereDate('import_products.received_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('import_products.status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('allImportDetailForUser', compact('import_products', 'pagination', 'branchs'));
    }

    public function getImportProduct(Request $request)
    {
        $import_product = Import_products::select('import_products.*')
            ->join('lot', 'lot.id', 'import_products.lot_id')
            ->where('code', $request->id)
            ->where('lot.receiver_branch_id', Auth::user()->branch_id)
            ->orderBy('import_products.id', 'desc')
            ->first();

        if ($import_product) {
            return response()->json($import_product);
        } else {
            return response()->json(['error' => '1']);
        }
    }

    public function receiveImport(Request $request)
    {
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $result = Import_products::query();

        $result
            ->select('import_products.*', 'branchs.branch_name')
            ->join('branchs', 'import_products.receiver_branch_id', 'branchs.id')
            ->where('import_products.receiver_branch_id', Auth::user()->branch_id);

        if ($request->receive_date != '') {
            $result->whereDate('import_products.created_at', '=', $request->receive_date);
        }
        if ($request->id != '') {
            $result->where('import_products.id', $request->id);
        }

        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->send_branch != '') {
            $result->where('sender_branch_id', $request->send_branch);
        }

        $all_products = $result->orderBy('import_products.id', 'desc')->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $products = $result
            ->orderBy('import_products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil(sizeof($all_products) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_products),
        ];

        return view('receiveimport', compact('products', 'pagination', 'branchs'));
    }

    public function deleteImportItem(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $lot = Lots::where('id', $request->lot_id)
            ->orderBy('id', 'DESC')
            ->first();

        Lots::where('id', $request->lot_id)->update([
            'total_base_price' => $lot->total_base_price - $request->base_price * $request->weight,
            'total_price' => $lot->total_price - $request->real_price * $request->weight,
            'weight_kg' => $lot->weight_kg - ($request->weight_type == 'm' ? 0 : $request->weight),
        ]);

        $import_product = Import_products::where('id', $request->lot_item_id);
        $import_product->delete();

        $count_import_product = Import_products::where('lot_id', $request->lot_id)->count();
        if ($count_import_product < 1) {
            $lot->delete();
        }

        return redirect()
            ->back()
            ->with(['error' => 'insert_success']);
    }

    public function changeImportItemWeight(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $import_product = Import_products::where('id', $request->lot_item_id_in_weight)->first();

        $lot = Lots::where('id', $request->lot_id_in_weight)
            ->orderBy('id', 'DESC')
            ->first();

        Lots::where('id', $request->lot_id_in_weight)->update([
            'total_base_price' => $lot->total_base_price - ($import_product->base_price * $import_product->weight) + ($import_product->base_price * $request->weight_in_weight),
            'total_price' => $lot->total_price - ($import_product->real_price * $import_product->weight) + ($import_product->real_price * $request->weight_in_weight),
            'total_main_price' => $lot->total_price - ($import_product->real_price * $import_product->weight) + ($import_product->real_price * $request->weight_in_weight) + ($lot->fee ? $lot->fee : 0) + ($lot->pack_price ? $lot->pack_price : 0),
            'weight_m' => $lot->weight_m - ($import_product->weight_type == 'm' ? $import_product->weight : 0) + ($import_product->weight_type == 'm' ? $request->weight_in_weight : 0),
            'weight_kg' => $lot->weight_kg - ($import_product->weight_type == 'kg' ? $import_product->weight : 0) + ($import_product->weight_type == 'kg' ? $request->weight_in_weight : 0),
        ]);

        $import_product = Import_products::where('id', $request->lot_item_id_in_weight)->update([
            'weight' => $request->weight_in_weight,
        ]);

        return redirect()
            ->back()
            ->with(['error' => 'insert_success']);
    }

    public function deleteLot(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $lot = lots::where('id', $request->id);
        $lot->delete();
        $import_products = Import_products::where('lot_id', $request->id);
        $import_products->delete();
        return redirect()
            ->back()
            ->with(['error' => 'delete_success']);
    }

    public function paidLot(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        Lots::where('id', $request->id)->update([
            'payment_status' => 'paid',
        ]);

        return redirect()->back()->with(['error' => 'insert_success']);
    }

    public function changeImportWeight(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $base_price_kg = $request->lot_base_price_kg ? $request->lot_base_price_kg : 0;
        $real_price_kg = $request->lot_real_price_kg ? $request->lot_real_price_kg : 0;
        $base_price_m = $request->lot_base_price_m ? $request->lot_base_price_m : 0;
        $real_price_m = $request->lot_real_price_m ? $request->lot_real_price_m : 0;
        $weight_m = Import_products::where('lot_id', $request->lot_id_in_weight)
            ->where('weight_type', 'm')
            ->sum('weight');

        Lots::where('id', $request->lot_id_in_weight)->update([
            'weight_kg' => $request->weight_in_weight,
            'total_base_price_kg' => $base_price_kg * $request->weight_in_weight,
            'total_unit_kg' => $real_price_kg * $request->weight_in_weight,
            'total_base_price' => $base_price_kg * $request->weight_in_weight + $weight_m * $base_price_m,
            'total_price' => $real_price_kg * $request->weight_in_weight + $weight_m * $real_price_m,
            'total_main_price' => $real_price_kg * $request->weight_in_weight + $weight_m * $real_price_m + $request->fee + $request->pack_price,
            'lot_base_price_kg' => $base_price_kg,
            'lot_real_price_kg' => $real_price_kg,
            'lot_base_price_m' => $base_price_m,
            'lot_real_price_m' => $real_price_m,
        ]);

        Import_products::where('lot_id', $request->lot_id_in_weight)
            ->where('weight_type', 'kg')
            ->update([
                'base_price' => $base_price_kg,
                'real_price' => $real_price_kg,
                // 'total_base_price' => $base_price_kg,
                // 'total_real_price' => $real_price_kg,
            ]);

        Import_products::where('lot_id', $request->lot_id_in_weight)
            ->where('weight_type', 'm')
            ->update([
                'base_price' => $base_price_m,
                'real_price' => $real_price_m,
            ]);

        return redirect('importView')->with(['error' => 'insert_success']);
    }

    public function updateImport(Request $request)
    {
        if (Import_products::where('id', $request->id)->update(['received_at' => Carbon::now(), 'status' => 'received'])) {
            return redirect('receive')->with(['error' => 'insert_success']);
        } else {
            return redirect('receive')->with(['error' => 'not_insert']);
        }
    }

    public function successImport(Request $request)
    {
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $result = Import_products::query();

        $result
            ->select('import_products.*', 'branchs.branch_name')
            ->join('branchs', 'import_products.receiver_branch_id', 'branchs.id')
            ->where('import_products.receiver_branch_id', Auth::user()->branch_id)
            ->where('type', 'import');

        if ($request->receive_date != '') {
            $result->whereDate('import_products.created_at', '=', $request->receive_date);
        }
        if ($request->id != '') {
            $result->where('import_products.id', $request->id);
        }

        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->send_branch != '') {
            $result->where('sender_branch_id', $request->send_branch);
        }

        $all_products = $result->orderBy('import_products.id', 'desc')->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $products = $result
            ->orderBy('import_products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil(sizeof($all_products) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_products),
        ];

        return view('successImport', compact('products', 'pagination', 'branchs'));
    }

    public function money_ch(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $sum_income = 0;
        $sum_income = IncomeCh::sum('price');
        $sum_withdraw = WithdrawCh::sum('price');

        $result = User::query();

        $result
            ->select('users.name', 'users.last_name', 'users.thai_percent')
            ->where('is_ch_partner', 1)
            ->groupBy('users.id')
            ->groupBy('users.name')
            ->groupBy('users.thai_percent')
            ->groupBy('users.last_name');

        if ($request->product_id != '') {
            $result->where('users.name', $request->name);
        }

        if ($request->status != '') {
            $result->where('users.last_name', $request->last_name);
        }

        $all_users = $result->orderBy('users.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $users = $result
            ->orderBy('users.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_users / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_users,
        ];

        return view('moneyCh', compact('sum_income', 'all_users', 'users', 'pagination', 'sum_withdraw'));
    }

    public function withdraw_ch(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $sum_income = 0;
        $sum_income = IncomeCh::sum('price');

        $result = WithdrawCh::query();

        $result->select('withdraw_ch.*');

        $all_withdraws = $result->orderBy('id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $withdraws = $result
            ->orderBy('id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_withdraws / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_withdraws,
        ];

        return view('withdraw_ch', compact('sum_income', 'all_withdraws', 'withdraws', 'pagination'));
    }

    public function withdraw_detail_ch($id)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $sum_withdraw_chIncomeCh = 0;
        $sum_withdraw_ch = WithdrawCh::where('id', $id)->sum('price');

        $result = User::query();

        $result->select('users.*')->where('is_ch_partner', 1);

        $all_users = $result->orderBy('id', 'desc')->count();

        $users = $result->orderBy('id', 'desc')->get();
        $new_users = [];
        foreach ($users as $key => $user) {
            $n['id'] = $user->id;
            $n['name'] = $user->name;
            $n['last_name'] = $user->last_name;
            $n['price'] = ($sum_withdraw_ch / 100) * $user->thai_percent;

            array_push($new_users, $n);
        }

        $users = $new_users;

        return view('withdraw_detail_ch', compact('all_users', 'users'));
    }

    public function addWithDrawCh(Request $request)
    {
        date_default_timezone_set('Asia/Bangkok');
        $date_now = date('Y-m-d H:i:s', time());

        $withdraw = new WithdrawCh();
        $withdraw->price = $request->price;
        $withdraw->updated_at = $date_now;
        $withdraw->created_at = $date_now;
        $withdraw->save();

        return redirect('withdraw_ch');
    }

    public function mainReportPrint(Request $request)
    {
        // Set higher limits for large reports
        // ini_set('max_execution_time', '300');
        // ini_set('memory_limit', '1024M');

        $delivery_round_id = $request->delivery_round_id;

        $imagesSub = DB::table('receipt_images')
            ->select(DB::raw('bill_id, GROUP_CONCAT(receipt_image SEPARATOR \',\') AS imgs'))
            ->groupBy('bill_id');

        $bill_query = DB::table('bills')
            ->select(DB::raw("
                    bills.id AS bill_id,
                    branchs.branch_name AS branch_name,
                    IFNULL(images.imgs, '') AS receipt_images,
                    SUM(lot.total_base_price) AS total_base_price,
                    SUM(lot.total_price) AS total_price,
                    SUM(lot.fee) AS fee,
                    SUM(lot.pack_price) AS pack_price,
                    SUM(lot.service_charge) AS service_charge,
                    SUM(lot.weight_kg) AS weight_kg,
                    SUM(lot.weight_m) AS weight_m
                "))
            ->join('branchs', 'bills.branch_id', '=', 'branchs.id')
            ->leftJoin('lot', 'bills.id', '=', 'lot.bill_id')
            ->leftJoinSub($imagesSub, 'images', function ($join) {
                $join->on('bills.id', '=', 'images.bill_id');
            })
            ->where('lot.delivery_round_id', $delivery_round_id)
            ->groupBy('bills.id', 'branchs.branch_name', 'images.imgs')
            ->orderBy('bills.id', 'desc');

        $bills = $bill_query->get();

        $bills = $bills->map(function ($bill) {
            // แปลง receipt_images จาก string เป็น array
            $receiptImages = $bill->receipt_images
                ? explode(',', $bill->receipt_images)
                : [];
            $bill->receipt_images = $receiptImages;
            return $bill;
        });

        // dd($bills);

        $expenditure_query = Expenditure::query();
        $expenditure_query->select('expenditure.*', 'users.name')
            ->join('users', 'expenditure.user_id', 'users.id')
            ->where('expenditure.delivery_round_id', $delivery_round_id)
            ->orderBy('expenditure.id', 'desc');
        $expenditures = $expenditure_query->get();

        $delivery_round = Delivery_rounds::where('id', $delivery_round_id)
            ->first();

        $data = [
            'delivery_round' => $delivery_round,
            'bills' => $bills,
            'expenditures' => $expenditures,
        ];

        $pdf = PDF::loadView(
            'pdf.mainReportPrint',
            $data,
            [],
            [
                'format' => 'A4',
                'orientation' => 'landscape',
                'custom_font_dir' => base_path('resources/fonts/'),
                'custom_font_data' => [
                    'defago' => [ // must be lowercase and snake_case
                        'R'  => 'defago-noto-sans-lao.ttf',    // regular font
                        'B'  => 'DefagoNotoSansLaoBold.ttf',    // bold font
                    ]
                    // ...add as many as you want.
                ]
            ]
        );

        return $pdf->stream('main_report.pdf');
    }

    public function mainReport(Request $request)
    {
        $delivery_round_id = null;
        $bills = collect();
        $expenditures = collect();

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $delivery_rounds = Delivery_rounds::orderBy('id', 'desc')->get();

        if ($request->delivery_round_id != '') {
            $delivery_round_id = $request->delivery_round_id;

            $imagesSub = DB::table('receipt_images')
                ->select(DB::raw('bill_id, GROUP_CONCAT(receipt_image SEPARATOR \',\') AS imgs'))
                ->groupBy('bill_id');

            $bill_query = DB::table('bills')
                ->select(DB::raw("
                    bills.id AS bill_id,
                    branchs.branch_name AS branch_name,
                    IFNULL(images.imgs, '') AS receipt_images,
                    SUM(lot.total_base_price) AS total_base_price,
                    SUM(lot.total_price) AS total_price,
                    SUM(lot.fee) AS fee,
                    SUM(lot.pack_price) AS pack_price,
                    SUM(lot.service_charge) AS service_charge,
                    SUM(lot.weight_kg) AS weight_kg,
                    SUM(lot.weight_m) AS weight_m
                "))
                ->join('branchs', 'bills.branch_id', '=', 'branchs.id')
                ->leftJoin('lot', 'bills.id', '=', 'lot.bill_id')
                ->leftJoinSub($imagesSub, 'images', function ($join) {
                    $join->on('bills.id', '=', 'images.bill_id');
                })
                ->where('lot.delivery_round_id', $delivery_round_id)
                ->groupBy('bills.id', 'branchs.branch_name', 'images.imgs')
                ->orderBy('bills.id', 'desc');

            $bills = $bill_query->get();

            foreach ($bills as $bill) {
                $bill->receipt_images = $bill->receipt_images
                    ? explode(',', $bill->receipt_images)
                    : [];
            }

            $expenditure_query = Expenditure::query();
            $expenditure_query->select('expenditure.*', 'users.name')
                ->join('users', 'expenditure.user_id', 'users.id')
                ->where('expenditure.delivery_round_id', $delivery_round_id)
                ->orderBy('expenditure.id', 'desc');
            $expenditures = $expenditure_query->get();
        }

        return view('mainReport', compact('branchs', 'delivery_rounds', 'delivery_round_id', 'bills', 'expenditures'));
    }

    public function insertBill(Request $request)
    {
        // Validation
        $request->validate([
            'lot_ids' => 'required|array|min:1',
            'lot_ids.*' => 'exists:lot,id', // ถ้าชื่อตารางจริงเป็น 'lots' ให้เปลี่ยนเป็น exists:lots,id
            'delivery_round' => 'required',
            'month' => 'required',
            'departure_time' => 'required',
            'branch_id' => 'required|exists:branchs,id',
        ], [
            'lot_ids.required' => 'กรุณาเลือกอย่างน้อย 1 ล็อต',
            'lot_ids.min' => 'กรุณาเลือกอย่างน้อย 1 ล็อต',
            'lot_ids.*.exists' => 'รายการที่เลือกไม่ถูกต้อง',
            'branch_id.exists' => 'สาขาที่เลือกไม่ถูกต้อง',
        ]);

        DB::beginTransaction();
        try {
            $bill = new Bills();
            $bill->delivery_round = $request->delivery_round;
            $bill->month = $request->month;
            $bill->departure_time = $request->departure_time;
            $bill->branch_id = $request->branch_id;

            $bill->save();

            // อัปเดตล็อตทั้งหมด
            Lots::whereIn('id', $request->lot_ids)->update(['bill_id' => $bill->id]);

            DB::commit();

            return redirect('bills')->with('error', 'insert_success');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('insertBill error: ' . $e->getMessage());
            return redirect('bills')->with('error', 'not_insert');
        }
    }

    public function makeBill(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $delivery_rounds = Delivery_rounds::orderBy('id', 'desc')->get();

        $large_import_products = collect();
        $normal_import_products = collect();
        $sum_delivery_fee = null;
        $sum_pack_fee = null;
        $sum_service_charge = null;
        $receive_branch_id = null;
        $delivery_round_id = null;
        $bill = null;

        if ($request->receive_branch_id != '' && $request->delivery_round_id != '') {
            $receive_branch_id = $request->receive_branch_id;
            $delivery_round_id = $request->delivery_round_id;
            // สร้าง query พื้นฐานร่วมกัน
            $baseQuery = Import_products::query()
                ->join('lot', 'import_products.lot_id', '=', 'lot.id');

            $baseQueryLot = Lots::query();

            $baseQuery->where('lot.receiver_branch_id', $request->receive_branch_id);
            $baseQueryLot->where('lot.receiver_branch_id', $request->receive_branch_id);

            $baseQuery->where('lot.delivery_round_id', $request->delivery_round_id);
            $baseQueryLot->where('lot.delivery_round_id', $request->delivery_round_id);

            // ดึง large
            $large_import_products = (clone $baseQuery)
                ->where('lot.parcel_size', 'large')
                ->select('import_products.*')
                ->orderBy('import_products.id', 'desc')
                ->get();

            // ดึง normal แบบ SUM weight
            $normal_import_products = (clone $baseQueryLot)
                ->where('lot.parcel_size', 'normal')
                ->selectRaw("COALESCE(SUM(lot.weight_kg), 0) as weight, lot_base_price_kg, lot_real_price_kg")
                ->groupBy('lot_base_price_kg')
                ->groupBy('lot_real_price_kg')
                ->get();

            $sum_delivery_fee = (clone $baseQueryLot)
                ->selectRaw("COALESCE(SUM(lot.fee), 0) as fee")
                ->first();

            $sum_pack_fee = (clone $baseQueryLot)
                ->selectRaw("COALESCE(SUM(lot.pack_price), 0) as pack_price")
                ->first();

            $sum_service_charge = (clone $baseQueryLot)
                ->selectRaw("COALESCE(SUM(lot.service_charge), 0) as service_charge")
                ->first();

            $imagesSub = DB::table('receipt_images')
                ->select(DB::raw('bill_id, GROUP_CONCAT(DISTINCT receipt_image SEPARATOR \',\') AS imgs'))
                ->groupBy('bill_id');

            $bill = Bills::select('bills.*', DB::raw("IFNULL(images.imgs, '') AS receipt_images"))
                ->leftJoinSub($imagesSub, 'images', function ($join) {
                    $join->on('bills.id', '=', 'images.bill_id');
                })
                ->where('bills.branch_id', $request->receive_branch_id)
                ->where('bills.delivery_round_id', $request->delivery_round_id)
                ->orderBy('bills.id', 'desc')
                ->first();

            // แปลงเป็น array (ถ้าต้องการ)
            if ($bill) {
                $bill->receipt_images = $bill->receipt_images !== ''
                    ? array_map('trim', explode(',', $bill->receipt_images))
                    : [];
            }
        }

        return view('makeBill', compact(
            'branchs',
            'delivery_rounds',
            'large_import_products',
            'normal_import_products',
            'sum_delivery_fee',
            'sum_pack_fee',
            'sum_service_charge',
            'receive_branch_id',
            'delivery_round_id',
            'bill'
        ));
    }

    public function printBill(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        if ($request->receive_branch_id === '' || $request->delivery_round_id === '') {
            return redirect('makeBill');
        } else {
            $branch = Branchs::where('id', $request->receive_branch_id)
                ->where('branchs.enabled', '1')
                ->first();

            $delivery_round = Delivery_rounds::where('id', $request->delivery_round_id)
                ->first();

            // print_r($delivery_round);

            // สร้าง query พื้นฐานร่วมกัน
            $baseQuery = Import_products::query()
                ->join('lot', 'import_products.lot_id', '=', 'lot.id');

            $baseQueryLot = Lots::query();

            $baseQuery->where('lot.receiver_branch_id', $request->receive_branch_id);
            $baseQueryLot->where('lot.receiver_branch_id', $request->receive_branch_id);

            $baseQuery->where('lot.delivery_round_id', $request->delivery_round_id);
            $baseQueryLot->where('lot.delivery_round_id', $request->delivery_round_id);

            // ดึง large
            $large_import_products = (clone $baseQuery)
                ->where('lot.parcel_size', 'large')
                ->select('import_products.*')
                ->orderBy('import_products.id', 'desc')
                ->get();

            // ดึง normal แบบ SUM weight
            $normal_import_products = (clone $baseQueryLot)
                ->where('lot.parcel_size', 'normal')
                ->selectRaw("COALESCE(SUM(lot.weight_kg), 0) as weight, lot_base_price_kg, lot_real_price_kg")
                ->groupBy('lot_base_price_kg')
                ->groupBy('lot_real_price_kg')
                ->get();

            $sum_delivery_fee = (clone $baseQueryLot)
                ->selectRaw("COALESCE(SUM(lot.fee), 0) as fee")
                ->first();

            $sum_pack_fee = (clone $baseQueryLot)
                ->selectRaw("COALESCE(SUM(lot.pack_price), 0) as pack_price")
                ->first();

            $sum_service_charge = (clone $baseQueryLot)
                ->selectRaw("COALESCE(SUM(lot.service_charge), 0) as service_charge")
                ->first();

            $imagesSub = DB::table('receipt_images')
                ->select(DB::raw('bill_id, GROUP_CONCAT(DISTINCT receipt_image SEPARATOR \',\') AS imgs'))
                ->groupBy('bill_id');

            $bill = Bills::select('bills.*', DB::raw("IFNULL(images.imgs, '') AS receipt_images"))
                ->leftJoinSub($imagesSub, 'images', function ($join) {
                    $join->on('bills.id', '=', 'images.bill_id');
                })
                ->where('bills.branch_id', $request->receive_branch_id)
                ->where('bills.delivery_round_id', $request->delivery_round_id)
                ->orderBy('bills.id', 'desc')
                ->first();

            // แปลงเป็น array (ถ้าต้องการ)
            if ($bill) {
                $bill->receipt_images = $bill->receipt_images !== ''
                    ? array_map('trim', explode(',', $bill->receipt_images))
                    : [];
            }

            $data = [
                'branch' => $branch,
                'delivery_round' => $delivery_round,
                'large_import_products' => $large_import_products,
                'normal_import_products' => $normal_import_products,
                'sum_delivery_fee' => $sum_delivery_fee,
                'sum_pack_fee' => $sum_pack_fee,
                'sum_service_charge' => $sum_service_charge,
                'bill' => $bill,
            ];

            $pdf = PDF::loadView(
                'pdf.printBill',
                $data,
                [],
                [
                    'format' => 'A4',
                    // 'orientation' => 'landscape',
                    'custom_font_dir' => base_path('resources/fonts/'),
                    'custom_font_data' => [
                        'defago' => [ // must be lowercase and snake_case
                            'R'  => 'defago-noto-sans-lao.ttf',    // regular font
                            'B'  => 'DefagoNotoSansLaoBold.ttf',    // bold font
                        ]
                        // ...add as many as you want.
                    ]
                ]
            );

            return $pdf->stream('document.pdf');
        }
    }

    public function payBill(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        DB::beginTransaction();

        try {
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');

                // ตรวจสอบว่าเป็นรูปภาพ
                $request->validate([
                    'receipt' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB
                ]);

                $fileName = 'receipt_' . time() . '.jpg';
                $path = $_SERVER['DOCUMENT_ROOT'] . '/img/receipts/' . $fileName;

                $image = new Image();
                $image->load($file)
                    ->resizeToWidth(300) // ปรับขนาดตามความกว้าง
                    ->save($path);

                $receiptPath = $fileName;
            }

            $bill = new Bills();
            $bill->delivery_round_id = $request->delivery_round_id;
            $bill->branch_id = $request->branch_id;
            $bill->save();
            Lots::where('receiver_branch_id', $request->branch_id)
                ->where('delivery_round_id', $request->delivery_round_id)
                ->update([
                    'payment_status' => 'paid',
                    'bill_id' => $bill->id
                ]);

            $receipt_image = new Receipt_images();
            $receipt_image->bill_id = $bill->id;
            if ($receiptPath) {
                $receipt_image->receipt_image = $receiptPath;
            }
            $receipt_image->save();

            DB::commit();

            return redirect()->back()->with('error', 'insert_success');
        } catch (\Throwable $e) {
            DB::rollBack();

            // ถ้ามีรูปถูกสร้างแล้วแต่ไม่สำเร็จ — ลบไฟล์ทิ้ง
            if (!empty($receiptFileName) && file_exists(public_path('img/receipts/' . $receiptFileName))) {
                unlink(public_path('img/receipts/' . $receiptFileName));
            }

            \Log::error('payBill error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'delivery_round_id' => $request->delivery_round_id,
                'branch_id' => $request->branch_id,
            ]);

            return redirect()->back()->with('error', 'not_insert');
        }
    }

    public function addReceiveImage(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        DB::beginTransaction();

        try {
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');

                // ตรวจสอบว่าเป็นรูปภาพ
                $request->validate([
                    'receipt' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB
                ]);

                $fileName = 'receipt_' . time() . '.jpg';
                $path = $_SERVER['DOCUMENT_ROOT'] . '/img/receipts/' . $fileName;

                $image = new Image();
                $image->load($file)
                    ->resizeToWidth(300) // ปรับขนาดตามความกว้าง
                    ->save($path);

                $receiptPath = $fileName;
            }

            $receipt_image = new Receipt_images();
            $receipt_image->bill_id = $request->bill_id;
            if ($receiptPath) {
                $receipt_image->receipt_image = $receiptPath;
            }
            $receipt_image->save();
            DB::commit();
            // dd($receipt_image->id);
            return redirect()->back()->with('error', 'insert_success');
        } catch (\Throwable $e) {
            DB::rollBack();

            // ถ้ามีรูปถูกสร้างแล้วแต่ไม่สำเร็จ — ลบไฟล์ทิ้ง
            if (!empty($receiptFileName) && file_exists(public_path('img/receipts/' . $receiptFileName))) {
                unlink(public_path('img/receipts/' . $receiptFileName));
            }

            \Log::error('payBill error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'delivery_round_id' => $request->delivery_round_id,
                'branch_id' => $request->branch_id,
            ]);

            return redirect()->back()->with('error', 'not_insert');
        }
    }
}
