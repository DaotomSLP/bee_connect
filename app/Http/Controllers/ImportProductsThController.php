<?php

namespace App\Http\Controllers;

use App\Models\Base_price_th;
use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Expenditure;
use App\Models\Import_products_th;
use App\Models\Lots_th;
use App\Models\Price_imports;
use App\Models\Price_imports_th;
use App\Models\Provinces;
use App\Models\Sale_import_th;
use App\Models\Sale_prices;
use App\Models\IncomeTh;
use App\Models\User;
use App\Models\WithdrawTh;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ImportProductsThController extends Controller
{
    public function index(Request $request)
    {
        $provinces = Provinces::all();
        $districts = Districts::all();
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        // $lots = Lots_th::all();

        // foreach ($lots as $key => $value) {
        //   $new_lot = ['total_main_price' => $value->total_price];
        //   Lots_th::where('id', $value->id)->update($new_lot);
        // }

        if (Auth::user()->is_admin == 1) {
            return view('th.importTh', compact('provinces', 'districts', 'branchs'));
        } else {
            return view('th.importForUserTh', compact('provinces', 'districts', 'branchs'));
        }
    }

    public function dailyImportTh(Request $request)
    {
        $to_date_now = date('Y-m-d', strtotime(Carbon::now()));

        if ($request->date != '') {
            $date =
                $request->date == Carbon::today()->toDateString()
                    ? [
                        Carbon::now()
                            ->subDays(1)
                            ->toDateString(),
                    ]
                    : $request->date.' 00:00:00';
            $to_date = $request->to_date == Carbon::today()->toDateString() ? [Carbon::tomorrow()->toDateString()] : $request->to_date." 23:59:59";
            $date_now = date('Y-m-d', strtotime($request->date));
            $to_date_now = date('Y-m-d', strtotime($request->to_date));
        } else {
            $date = [Carbon::today()->toDateString()];
            $to_date = [Carbon::tomorrow()->toDateString()];
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        $branch_id = Auth::user()->branch_id;

        $result = DB::table('lot_th')
            ->select(DB::raw('branchs.id as receiver_branch_id, branchs.branch_name as branch_name, sum(lot_th.total_price) as branch_total_price'))
            // ->join('import_products_th', 'lot_th.id', 'import_products_th.lot_id')
            ->join('branchs', 'lot_th.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot_th.created_at', [$date, $to_date])
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name');
        if (Auth::user()->branch_id == null) {
            $sum_base_price = Base_price_th::whereBetween('base_price_th.created_at', [$date, $to_date])->sum('price');

            $sum_real_price = Lots_th::whereBetween('lot_th.created_at', [$date, $to_date])
                ->where('payment_status', 'paid')
                ->sum('total_main_price');
                // echo($date);

            //   print_r($sum_real_price);exit;

            $sum_fee_price = Lots_th::whereBetween('lot_th.created_at', [$date, $to_date])->sum('fee');
            $sum_pack_price = Lots_th::whereBetween('lot_th.created_at', [$date, $to_date])->sum('pack_price');
        } else {
            $result->where('lot_th.receiver_branch_id', Auth::user()->branch_id);

            $sum_base_price = Lots_th::whereBetween('lot_th.created_at', [$date, $to_date])
                ->where('lot_th.receiver_branch_id', Auth::user()->branch_id)
                ->sum('total_main_price');
            $sum_real_price = Lots_th::whereBetween('lot_th.created_at', [$date, $to_date])
                // ->where('lot_th.receiver_branch_id', Auth::user()->branch_id)
                ->sum('total_sale_price');

            $sum_fee_price = Lots_th::whereBetween('lot_th.created_at', [$date, $to_date])
                ->where('lot_th.receiver_branch_id', Auth::user()->branch_id)
                ->sum('fee');
            $sum_pack_price = Lots_th::whereBetween('lot_th.created_at', [$date, $to_date])
                ->where('lot_th.receiver_branch_id', Auth::user()->branch_id)
                ->sum('pack_price');
        }

        $sum_sale_profit = $sum_real_price - $sum_base_price;

        $sum_expenditure = Expenditure::whereBetween('created_at', [$date, $to_date])->sum('price');

        $sum_profit = $sum_real_price - $sum_base_price - $sum_expenditure;

        $all_branch_sale_totals = $result->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $branch_sale_totals = $result->limit(25)->get();

        $pagination = [
            'offsets' => ceil($all_branch_sale_totals / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_branch_sale_totals,
        ];

        $import_product_count = DB::table('lot_th')
            ->select(DB::raw('count(import_products_th.id) as count_import_product, lot_th.receiver_branch_id'))
            ->join('import_products_th', 'lot_th.id', 'import_products_th.lot_id')
            ->join('branchs', 'lot_th.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot_th.created_at', [$date, $to_date])
            ->groupBy('lot_th.receiver_branch_id')
            ->get();

        $result_unpaid = DB::table('lot_th')
            ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot_th.total_main_price) as branch_total_price'))
            // ->join('import_products_th', 'lot_th.id', 'import_products_th.lot_id')
            ->join('branchs', 'lot_th.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot_th.created_at', [$date, $to_date])
            ->where('lot_th.payment_status', 'not_paid')
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get();

        $result_paid = DB::table('lot_th')
            ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot_th.total_main_price) as branch_total_price'))
            // ->join('import_products_th', 'lot_th.id', 'import_products_th.lot_id')
            ->join('branchs', 'lot_th.receiver_branch_id', 'branchs.id')
            ->whereBetween('lot_th.created_at', [$date, $to_date])
            ->where('lot_th.payment_status', '<>', 'not_paid')
            ->groupBy('branchs.id')
            ->groupBy('branchs.branch_name')
            ->get();

        $sum_share = 0;
        if (Auth::user()->is_thai_partner == 1) {
            $sum_share = $sum_sale_profit * (Auth::user()->thai_percent / 100);
        }

        return view('th.dailyimportTh', compact('sum_base_price', 'sum_real_price', 'sum_sale_profit', 'sum_profit', 'sum_expenditure', 'date_now', 'branch_sale_totals', 'pagination', 'to_date_now', 'import_product_count', 'result_paid', 'result_unpaid', 'sum_fee_price', 'sum_pack_price', 'sum_share'));
    }

    public function addImportTh(Request $request)
    {
        if (Auth::user()->is_thai_admin != 1) {
            return redirect('access_denied');
        }

        $provinces = Provinces::all();
        $districts = Districts::all();
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();
        $result = Import_products_th::query();

        $result->select('import_products_th.*', 'receive.branch_name')->join('branchs As receive', 'import_products_th.receive_branch_id', 'receive.id');

        if ($request->send_date != '') {
            $result->whereDate('import_products_th.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products_th.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('import_products_th.status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receive_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('th.addImportTh', compact('provinces', 'districts', 'branchs', 'import_products', 'pagination'));
    }

    public function addImportProductTh(Request $request)
    {
        if (Auth::user()->is_thai_admin != 1) {
            return redirect('access_denied');
        }

        date_default_timezone_set('Asia/Bangkok');
        $date_now = date('Y-m-d H:i:s', time());

        $imp_th_id = import_products_th::select('id')
            ->orderBy('id', 'desc')
            ->first();
        $import_products_th = new import_products_th();
        $import_products_th->name = $request->name;
        $import_products_th->detail = $request->detail;
        $import_products_th->receive_branch_id = $request->receiver_branch_id;
        $import_products_th->created_at = $date_now;
        $import_products_th->updated_at = $date_now;
        if ($request->code == '') {
            $import_products_th->code = $imp_th_id ? 'BC' . strval($imp_th_id['id'] + 1) : 'BC10000000';
        } else {
            $import_products_th->code = $request->code;
        }
        $import_products_th->status = 'waiting';

        if ($import_products_th->save()) {
            return redirect('addImportTh')->with(['error' => 'insert_success', 'id' => $import_products_th->id]);
        } else {
            return redirect('addImportTh')->with(['error' => 'not_success']);
        }
    }

    public function saleImportTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $sale_price_gram = Sale_prices::where('weight_type', 'kg')
            ->where('branch_id', Auth::user()->branch_id)
            ->orderBy('id', 'DESC')
            ->first();

        $sale_price_m = Sale_prices::where('weight_type', 'm')
            ->where('branch_id', Auth::user()->branch_id)
            ->orderBy('id', 'DESC')
            ->first();

        return view('th.saleImportTh', compact('sale_price_gram', 'sale_price_m'));
    }

    public function checkImportProductTh(Request $request)
    {
        $import_product_th = import_products_th::select('import_products_th.*')
            ->where('status', 'waiting')
            ->where('code', $request->id)
            ->where('receive_branch_id', $request->receive_branch)
            ->orderBy('import_products_th.id', 'desc')
            ->first();

        if ($import_product_th) {
            return response()->json($import_product_th);
        } else {
            return response()->json(['error' => '1']);
        }
    }

    public function importProductTh(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        if ($request->item_id) {
            $sum_price = 0;
            // $sum_base_price = 0;
            // $sum_m_weight = 0;

            $count = 0;
            // foreach ($request->weight_type as $weight_type) {
            //   if ($weight_type == 'm') {
            //     $sum_m_weight += $request->weight[$count];
            //   } else {
            //     if ($request->weight_kg <= 0) {
            //       return redirect('importTh')->with(['error' => 'not_insert']);
            //     }
            //   }

            //   $count++;
            // }

            foreach ($request->price as $price) {
                $sum_price += $price;
            }

            // foreach ($request->base_price as $base_price) {
            //   $sum_base_price += $base_price;
            // }

            // $default_price_kg = Price_imports::where('weight_type', 'kg')
            //   ->orderBy('id', 'DESC')->first();

            // $default_price_m = Price_imports::where('weight_type', 'm')
            //   ->orderBy('id', 'DESC')->first();

            // $sum_kg_base_price = ($request->base_price_kg == '' ? $default_price_kg->base_price : $request->base_price_kg) * $request->weight_kg;
            // $sum_m_base_price = ($request->base_price_m == '' ? $default_price_m->base_price : $request->base_price_m) * $sum_m_weight;
            // $sum_base_price = $sum_m_base_price + $sum_kg_base_price;

            // $sum_kg_price = ($request->real_price_kg == '' ? $default_price_kg->real_price : $request->real_price_kg) * $request->weight_kg;
            // $sum_m_price = ($request->real_price_m == '' ? $default_price_m->real_price : $request->real_price_m) * $sum_m_weight;
            // $sum_price = $sum_m_price + $sum_kg_price;
            date_default_timezone_set('Asia/Bangkok');
            $date_now = date('Y-m-d H:i:s', time());

            $lot = new Lots_th();
            $lot->receiver_branch_id = $request->receiver_branch_id;
            // $lot->weight_kg = $request->weight_kg;
            // $lot->total_base_price_kg = $sum_kg_base_price;
            // $lot->total_base_price_m = $sum_m_base_price;
            // $lot->total_base_price = $sum_base_price;
            $lot->total_main_price = $sum_price + $request->fee + $request->pack_price;
            $lot->total_price = $sum_price;
            // $lot->total_unit_m = $sum_m_price;
            // $lot->total_unit_kg = $sum_kg_price;
            $lot->status = 'sending';
            $lot->payment_status = 'not_paid';
            $lot->fee = $request->fee;
            $lot->pack_price = $request->pack_price;
            $lot->created_at = $date_now;
            $lot->updated_at = $date_now;
            // $lot->lot_real_price_kg = $request->real_price_kg;
            // $lot->lot_base_price_kg = $request->base_price_kg;
            // $lot->lot_real_price_m = $request->real_price_m;
            // $lot->lot_base_price_m = $request->base_price_m;

            if ($lot->save()) {
                $count = 0;
                foreach ($request->item_id as $product_id) {
                    $product = [];

                    // $product["base_price"] = $request->base_price[$count];
                    $product['real_price'] = $request->price[$count];
                    $product['receive_bc_at'] = $date_now;
                    // $product["total_base_price"] = $request->base_price[$count];
                    $product['total_real_price'] = $request->price[$count];
                    $product['total_sale_price'] = 0;
                    $product['weight'] = $request->weight[$count];

                    $product['status'] = 'sending';
                    $product['lot_id'] = $lot->id;

                    import_products_th::where('code', $product_id)->update($product);

                    $count++;
                }
                return redirect('importTh')->with(['error' => 'insert_success', 'id' => $lot->id]);
            } else {
                return redirect('importTh')->with(['error' => 'not_insert']);
            }
        } else {
            return redirect('importTh')->with(['error' => 'not_insert']);
        }
    }

    public function insertImportForUserTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        if ($request->item_id) {
            $count = 0;
            foreach ($request->item_id as $product_code) {
                $import_product = import_products_th::where('id', $product_code)
                    ->orderBy('id', 'DESC')
                    ->first();

                date_default_timezone_set('Asia/Bangkok');
                $date_now = date('Y-m-d H:i:s', time());
                $new_import_product_update = [
                    'received_at' => $date_now,
                    'status' => 'received',
                ];

                if (import_products_th::where('id', $import_product->id)->update($new_import_product_update)) {
                    $count_status = import_products_th::where('status', 'sending')
                        ->where('lot_id', $import_product->lot_id)
                        ->count();
                    if ($count_status < 1) {
                        Lots_th::where('id', $import_product->lot_id)->update(['status' => 'received']);
                    } else {
                        Lots_th::where('id', $import_product->lot_id)->update(['status' => 'not_full']);
                    }
                }

                $count++;
            }

            return redirect('importTh')->with(['error' => 'insert_success']);
        } else {
            return redirect('importTh')->with(['error' => 'not_insert']);
        }
    }

    public function insertSaleImportTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        if ($request->items) {
            $sum_price = 0;

            foreach ($request->items as $key => $value) {
                $sum_price += $value['price'];
            }

            date_default_timezone_set('Asia/Bangkok');
            $date_now = date('Y-m-d H:i:s', time());

            $sale_import = new Sale_import_th();
            $sale_import->branch_id = Auth::user()->branch_id;
            $sale_import->total = $sum_price - ($request->discount == '' ? 0 : $request->discount);
            $sale_import->discount = $request->discount == '' ? 0 : $request->discount;
            $sale_import->subtotal = $sum_price;
            $sale_import->sale_type = 'normal';
            $sale_import->created_at = $date_now;
            $sale_import->updated_at = $date_now;

            if ($sale_import->save()) {
                foreach ($request->items as $key => $value) {
                    $import_product = import_products_th::where('id', $value['id'])
                        ->orderBy('id', 'DESC')
                        ->first();

                    $lot = Lots_th::where('id', $import_product->lot_id)
                        ->orderBy('id', 'DESC')
                        ->first();

                    date_default_timezone_set('Asia/Bangkok');
                    $date_now = date('Y-m-d H:i:s', time());

                    $new_import_product_update = [
                        'status' => 'success',
                        'success_at' => $date_now,
                        'sale_id' => $sale_import->id,
                        'sale_price' => $value['price'],
                        'shipping_fee' => 0,
                        // 'weight' => $value["weight"],
                        'total_sale_price' => $value['price'],
                    ];
                    // }

                    if (import_products_th::where('id', $value['id'])->update($new_import_product_update)) {
                        $import_product = import_products_th::where('id', $value['id'])
                            ->orderBy('id', 'DESC')
                            ->first();

                        $count_status = import_products_th::where('status', 'success')
                            ->where('lot_id', $import_product->lot_id)
                            ->get();
                        $all = import_products_th::where('lot_id', $import_product->lot_id)->get();

                        $sum_sale_price = import_products_th::where('lot_id', $import_product->lot_id)
                            ->where('status', 'success')
                            ->sum('total_sale_price');
                        Lots_th::where('id', $import_product->lot_id)->update(['total_sale_price' => $sum_sale_price]);

                        if ($count_status == $all) {
                            Lots_th::where('id', $import_product->lot_id)->update(['status' => 'success']);
                        }
                    } else {
                        import_products_th::where('sale_id', $sale_import->id)
                            ->where('weight_type', 'kg')
                            ->update([
                                'status' => 'received',
                                'success_at' => '',
                                'sale_id' => '',
                                'sale_price' => '',
                                // 'weight' => '',
                                'total_sale_price' => '',
                                'shipping_fee' => null,
                            ]);

                        import_products_th::where('sale_id', $sale_import->id)
                            ->where('weight_type', 'm')
                            ->update([
                                'status' => 'received',
                                'success_at' => '',
                                'sale_id' => '',
                                'sale_price' => '',
                                'total_sale_price' => '',
                                'shipping_fee' => null,
                            ]);
                        $sale = Sale_import_th::where('id', $sale_import->id);
                        $sale->delete();
                        return response()->json(['id' => 0]);
                    }
                }
                return response()->json(['id' => $sale_import->id]);
                // return redirect('saleImport')->with(['error' => 'insert_success', 'id' => $sale_import->id]);
            } else {
                return response()->json(['id' => 0]);
            }
        } else {
            return response()->json(['id' => 0]);
        }
    }

    public function insertSaleImportForRiderTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        if ($request->items) {
            $sum_price = 0;

            foreach ($request->items as $key => $value) {
                $sum_price += $value['price'] * $value['weight'] + $value['shipping_fee'] * $value['weight'];
            }

            date_default_timezone_set('Asia/Bangkok');
            $date_now = date('Y-m-d H:i:s', time());

            $sale_import = new Sale_import_th();
            $sale_import->branch_id = Auth::user()->branch_id;
            $sale_import->total = $sum_price - ($request->discount == '' ? 0 : $request->discount);
            $sale_import->discount = $request->discount == '' ? 0 : $request->discount;
            $sale_import->subtotal = $sum_price;
            $sale_import->sale_type = 'tohouse';
            $sale_import->created_at = $date_now;
            $sale_import->updated_at = $date_now;

            if ($sale_import->save()) {
                foreach ($request->items as $key => $value) {
                    $import_product = import_products_th::where('id', $value['id'])
                        ->orderBy('id', 'DESC')
                        ->first();

                    $lot = Lots_th::where('id', $import_product->lot_id)
                        ->orderBy('id', 'DESC')
                        ->first();

                    date_default_timezone_set('Asia/Bangkok');
                    $date_now = date('Y-m-d H:i:s', time());

                    if ($import_product->weight_type != 'm') {
                        $new_import_product_update = [
                            'status' => 'success',
                            'success_at' => $date_now,
                            'sale_id' => $sale_import->id,
                            'weight' => $value['weight'],
                            'shipping_fee' => $value['shipping_fee'] * $value['weight'],
                            'sale_price' => $value['price'],
                            'total_base_price' => ($lot->total_base_price_kg / $lot->weight_kg) * $value['weight'],
                            'total_real_price' => ($lot->total_unit_kg / $lot->weight_kg) * $value['weight'],
                            'total_sale_price' => $value['price'] * $value['weight'],
                        ];
                    } else {
                        $new_import_product_update = [
                            'status' => 'success',
                            'success_at' => $date_now,
                            'sale_id' => $sale_import->id,
                            'sale_price' => $value['price'],
                            'shipping_fee' => $value['shipping_fee'] * $value['weight'],
                            'weight' => $value['weight'],
                            'total_sale_price' => $value['price'] * $value['weight'],
                        ];
                    }

                    if (import_products_th::where('id', $value['id'])->update($new_import_product_update)) {
                        $import_product = import_products_th::where('id', $value['id'])
                            ->orderBy('id', 'DESC')
                            ->first();

                        $count_status = import_products_th::where('status', 'success')
                            ->where('lot_id', $import_product->lot_id)
                            ->get();
                        $all = import_products_th::where('lot_id', $import_product->lot_id)->get();

                        $sum_sale_price = import_products_th::where('lot_id', $import_product->lot_id)
                            ->where('status', 'success')
                            ->sum('total_sale_price');
                        Lots_th::where('id', $import_product->lot_id)->update(['total_sale_price' => $sum_sale_price]);

                        if ($count_status == $all) {
                            Lots_th::where('id', $import_product->lot_id)->update(['status' => 'success']);
                        }
                    } else {
                        import_products_th::where('sale_id', $sale_import->id)
                            ->where('weight_type', 'kg')
                            ->update([
                                'status' => 'received',
                                'success_at' => '',
                                'sale_id' => '',
                                'sale_price' => '',
                                'weight' => '',
                                'total_sale_price' => '',
                                'shipping_fee' => null,
                            ]);

                        import_products_th::where('sale_id', $sale_import->id)
                            ->where('weight_type', 'm')
                            ->update([
                                'status' => 'received',
                                'success_at' => '',
                                'sale_id' => '',
                                'sale_price' => '',
                                'total_sale_price' => '',
                                'shipping_fee' => null,
                            ]);
                        $sale = Sale_import_th::where('id', $sale_import->id);
                        $sale->delete();
                        return response()->json(['id' => 0]);
                    }
                }
                return response()->json(['id' => $sale_import->id]);
                // return redirect('saleImport')->with(['error' => 'insert_success', 'id' => $sale_import->id]);
            } else {
                return response()->json(['id' => 0]);
            }
        } else {
            return response()->json(['id' => 0]);
        }
    }

    public function importViewTh(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();
        $result = Lots_th::query();

        $result->select('lot_th.*', 'receive.branch_name as receiver_branch_name')->join('branchs As receive', 'lot_th.receiver_branch_id', 'receive.id');

        // if (Auth::user()->is_admin != '1') {
        //   $result->where('import_products_th.sender_branch_id', Auth::user()->branch_id);
        // }

        if ($request->send_date != '') {
            $result->whereDate('lot_th.created_at', $request->send_date);
        }
        if ($request->id != '') {
            $result->where('lot_th.id', $request->id);
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

        $all_lots = $result->orderBy('lot_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $lots = $result
            ->orderBy('lot_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_lots / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_lots,
        ];

        return view('th.importViewTh', compact('branchs', 'lots', 'pagination'));
    }

    public function saleViewTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $result = Sale_import_th::query();

        $result->select('sale_import_th.*')->where('branch_id', Auth::user()->branch_id);

        if ($request->send_date != '') {
            $result->whereDate('sale_import_th.created_at', '=', $request->send_date);
        }
        if ($request->id != '') {
            $result->where('sale_import_th.id', $request->id);
        }

        $all_sale_imports = $result->orderBy('sale_import_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $sale_imports = $result
            ->orderBy('sale_import_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_sale_imports / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_sale_imports,
        ];

        return view('th.saleViewTh', compact('sale_imports', 'pagination'));
    }

    public function importViewForUserTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();
        $result = Lots_th::query();

        $result
            ->select('lot_th.*', 'receive.branch_name as receiver_branch_name')
            ->join('branchs As receive', 'lot_th.receiver_branch_id', 'receive.id')
            ->where('receiver_branch_id', Auth::user()->branch_id);

        // if (Auth::user()->is_admin != '1') {
        //   $result->where('import_products_th.sender_branch_id', Auth::user()->branch_id);
        // }

        if ($request->send_date != '') {
            $result->whereDate('lot_th.created_at', '=', $request->send_date);
        }
        if ($request->id != '') {
            $result->where('lot_th.id', $request->id);
        }
        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_lots = $result->orderBy('lot_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $lots = $result
            ->orderBy('lot_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_lots / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_lots,
        ];

        return view('th.importViewTh', compact('branchs', 'lots', 'pagination'));
    }

    public function addImportThPdf($id)
    {
        $import_product = Import_products_th::find($id);
        $receive_branch = Branchs::find($import_product->receive_branch_id);

        $data = [
            'id' => $import_product->code,
            'date' => date('d-m-Y', strtotime($import_product->created_at)),
            'to' => $receive_branch->branch_name,
            'detail' => $import_product->detail,
        ];
        $pdf = PDF::loadView('pdf.addImportTh', $data);
        return $pdf->stream('document.pdf');
    }

    public function reportTh($id)
    {
        $lot = Lots_th::find($id);
        $receive_branch = Branchs::find($lot->receiver_branch_id);
        $import_product_data = Import_products_th::where('lot_id', $id)->get();
        $data = [
            'id' => $lot->id,
            'date' => date('d-m-Y', strtotime($lot->created_at)),
            'to' => $receive_branch->branch_name,
            'weight_kg' => $lot->weight_kg,
            'price' => $lot->total_main_price,
            'pack_price' => $lot->pack_price,
            'fee' => $lot->fee,
            'import_product_data' => $import_product_data,
        ];
        $pdf = PDF::loadView('pdf.importTh', $data);
        return $pdf->stream('document.pdf');
    }

    public function salereportTh($id)
    {
        $sale = Sale_import_th::find($id);
        $items = import_products_th::where('sale_id', $id)->get();

        $data = [
            'id' => $sale->id,
            'date' => date('d-m-Y', strtotime($sale->created_at)),
            'price' => $sale->total,
            'discount' => $sale->discount,
            'items' => $items,
        ];

        $pdf = PDF::loadView('pdf.saleTh', $data);
        return $pdf->stream('document.pdf');
    }

    public function importDetailTh(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $result = import_products_th::query();

        $result->select('import_products_th.*')->where('lot_id', $request->id);

        if ($request->send_date != '') {
            $result->whereDate('import_products_th.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products_th.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        // echo ($import_products));
        // exit;

        return view('th.importDetailTh', compact('branchs', 'import_products', 'pagination'));
    }

    public function saleDetailTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $result = import_products_th::query();

        $result
            ->select('import_products_th.*')
            ->join('sale_import_th', 'sale_import_th.id', 'import_products_th.sale_id')
            ->where('sale_import_th.id', $request->id);

        if ($request->send_date != '') {
            $result->whereDate('sale_import_th.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products_th.code', $request->product_id);
        }

        $all_import_products = $result->orderBy('sale_import_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('th.saleDetailTh', compact('import_products', 'pagination'));
    }

    public function importDetailForUser(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $result = import_products_th::query();

        $result
            ->select('import_products_th.*')
            ->join('lot_th', 'lot_th.id', 'import_products_th.lot_id')
            ->join('branchs As receive', 'lot_th.receiver_branch_id', 'receive.id')
            ->where('lot_id', $request->id)
            ->where('lot_th.receiver_branch_id', Auth::user()->branch_id);

        if ($request->send_date != '') {
            $result->whereDate('import_products_th.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products_th.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('import_products_th.status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('th.importDetailForUserTh', compact('branchs', 'import_products', 'pagination'));
    }

    public function importProductTrackTh(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)
            ->where('branchs.enabled', '1')
            ->get();

        $result = Import_products_th::query();

        $result->select('import_products_th.*', 'receive.branch_name')->join('branchs As receive', 'import_products_th.receive_branch_id', 'receive.id');

        if ($request->send_date != '') {
            $result->whereDate('import_products_th.created_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products_th.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('import_products_th.status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('th.allImportDetailTh', compact('branchs', 'import_products', 'pagination'));
    }

    public function importProductTrackForUserTh(Request $request)
    {
        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $branchs = Branchs::where('branchs.enabled', '1')
            ->get();

        $result = import_products_th::query();

        $result->select('import_products_th.*', 'receive.branch_name')->join('branchs As receive', 'import_products_th.receive_branch_id', 'receive.id');

        if ($request->send_date != '') {
            $result->whereDate('import_products_th.received_at', '=', $request->send_date);
        }

        if ($request->product_id != '') {
            $result->where('import_products_th.code', $request->product_id);
        }

        if ($request->status != '') {
            $result->where('import_products_th.status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receive_branch_id', $request->receive_branch);
        }

        $all_import_products = $result->orderBy('import_products_th.id', 'desc')->count();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $import_products = $result
            ->orderBy('import_products_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil($all_import_products / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_import_products,
        ];

        return view('th.allImportDetailForUserTh', compact('import_products', 'pagination', 'branchs'));
    }

    public function getImportProductTh(Request $request)
    {
        $import_product = import_products_th::select('import_products_th.*')
            ->join('lot_th', 'lot_th.id', 'import_products_th.lot_id')
            ->where('code', $request->id)
            ->where('lot_th.receiver_branch_id', Auth::user()->branch_id)
            ->orderBy('import_products_th.id', 'desc')
            ->first();

        if ($import_product) {
            return response()->json($import_product);
        } else {
            return response()->json(['error' => '1']);
        }
    }

    public function deleteImportItemThForWaiting(Request $request)
    {
        if (Auth::user()->is_thai_admin != 1) {
            return redirect('access_denied');
        }

        $id = $request->id;
        $import_product_data = Import_products_th::where('id', $id)
            ->orderBy('id', 'DESC')
            ->first();
        if ($import_product_data->status != 'waiting') {
            return redirect('addImportTh')->with(['error' => 'not_insert']);
        }
        $import_product = import_products_th::where('id', $id);
        $import_product->delete();

        return redirect('addImportTh')->with(['error' => 'insert_success']);
    }

    public function deleteImportItemTh(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $id = $request->id;
        $import_product_data = Import_products_th::where('id', $id)
            ->orderBy('id', 'DESC')
            ->first();
        if ($import_product_data->status != 'sending') {
            return redirect('addImportTh')->with(['error' => 'not_insert']);
        }
        $lot = Lots_th::where('id', $import_product_data->lot_id)
            ->orderBy('id', 'DESC')
            ->first();

        Lots_th::where('id', $import_product_data->lot_id)->update([
            'total_price' => $lot->total_price - $import_product_data->real_price,
            'total_main_price' => $lot->total_main_price - $import_product_data->real_price,
        ]);

        $import_product = import_products_th::where('id', $id);
        $import_product->delete();

        $count_import_product = import_products_th::where('lot_id', $import_product_data->lot_id)->count();
        if ($count_import_product < 1) {
            $lot->delete();
            return redirect('importViewTh');
        }

        return redirect('importDetailTh?id=' . $import_product_data->lot_id)->with(['error' => 'insert_success']);
    }

    public function changeImportItemWeightTh(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $import_product = import_products_th::where('id', $request->prod_id)->first();

        $lot = Lots_th::where('id', $request->lot_id)
            ->orderBy('id', 'DESC')
            ->first();

        Lots_th::where('id', $request->lot_id)->update([
            'total_price' => $lot->total_price - $import_product->real_price + $request->real_price,
            'total_main_price' => $lot->total_price - $import_product->real_price + $request->real_price + ($lot->fee ? $lot->fee : 0) + ($lot->pack_price ? $lot->pack_price : 0),
        ]);

        $import_product = import_products_th::where('id', $request->prod_id)->update([
            'weight' => $request->weight,
            'real_price' => $request->real_price,
        ]);

        return redirect('importDetailTh?id=' . $request->lot_id)->with(['error' => 'insert_success']);
    }

    public function deleteLotTh(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $lot = lots_th::where('id', $request->id);
        $lot->delete();
        $import_products = import_products_th::where('lot_id', $request->id);
        $import_products->delete();
        return redirect('importViewTh')->with(['error' => 'delete_success']);
    }

    public function paidLotTh(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $lot = Lots_th::where('id', $request->id)->update([
            'payment_status' => 'paid',
        ]);

        $sum_price = Lots_th::where('id', $request->id)->sum('total_main_price');

        $income_th = new IncomeTh();
        $income_th->price = $sum_price;
        $income_th->lot_id = $request->id;
        $income_th->save();
        return redirect()->back()->with(['error' => 'insert_success']);
    }

    public function changeImportWeightTh(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $base_price_kg = $request->lot_base_price_kg ? $request->lot_base_price_kg : 0;
        $real_price_kg = $request->lot_real_price_kg ? $request->lot_real_price_kg : 0;
        $base_price_m = $request->lot_base_price_m ? $request->lot_base_price_m : 0;
        $real_price_m = $request->lot_real_price_m ? $request->lot_real_price_m : 0;
        $weight_m = import_products_th::where('lot_id', $request->lot_id_in_weight)
            ->where('weight_type', 'm')
            ->sum('weight');

        Lots_th::where('id', $request->lot_id_in_weight)->update([
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

        return redirect('importViewTh')->with(['error' => 'insert_success']);
    }

    public function base_price_th(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        if ($request->date_search != '') {
            $date = date('Y-m-d H:i:s', strtotime($request->date_search));
            $date = date_create($date, timezone_open('Asia/Vientiane'));
            date_timezone_set($date, timezone_open('Africa/Accra'));
            $to_date = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($request->date_search)));
            $date_now = date('Y-m-d', strtotime($request->date));
        } else {
            $date = Carbon::today();
            $date = date_create($date, timezone_open('Asia/Vientiane'));
            date_timezone_set($date, timezone_open('Pacific/Nauru'));
            $to_date = Carbon::tomorrow();
            $date_now = date('Y-m-d', strtotime(Carbon::now()));
        }

        $result = Base_price_th::query();
        $date_now = date('Y-m-d', strtotime(Carbon::now()));

        $result
            ->select('base_price_th.*', 'users.name')
            ->join('users', 'base_price_th.user_id', 'users.id')
            ->whereBetween('base_price_th.created_at', [$date, $to_date]);

        if ($request->date_search != '') {
        }

        $all_base_price_th = $result->orderBy('base_price_th.id', 'desc')->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $base_price_th = $result
            ->orderBy('base_price_th.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' => ceil(sizeof($all_base_price_th) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_base_price_th),
        ];

        return view('th.base_price_th', compact('base_price_th', 'pagination', 'date_now'));
    }

    public function addBasePriceTh(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $base_price_th = new Base_price_th();
        $base_price_th->created_at = $request->date;
        $base_price_th->price = $request->price;
        $base_price_th->user_id = Auth::user()->id;
        $base_price_th->detail = $request->detail;

        if ($base_price_th->save()) {
            return redirect('base_price_th')->with(['error' => 'insert_success']);
        } else {
            return redirect('base_price_th')->with(['error' => 'not_insert']);
        }
    }

    public function money_th(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $sum_income = 0;
        $sum_income = IncomeTh::sum('price');
        $sum_withdraw = WithdrawTh::sum('price');

        $result = User::query();

        $result
            ->select('users.name', 'users.last_name', 'users.thai_percent')
            ->where('is_thai_partner', 1)
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

        return view('th.moneyTh', compact('sum_income', 'all_users', 'users', 'pagination', 'sum_withdraw'));
    }

    public function withdraw_th(Request $request)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $sum_income = 0;
        $sum_income = IncomeTh::sum('price');

        $result = WithdrawTh::query();

        $result->select('withdraw_th.*');

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

        return view('th.withdraw_th', compact('sum_income', 'all_withdraws', 'withdraws', 'pagination'));
    }

    public function withdraw_detail_th($id)
    {
        if (Auth::user()->is_owner != 1) {
            return redirect('access_denied');
        }

        $sum_withdraw_th = 0;
        $sum_withdraw_th = WithdrawTh::where('id', $id)->sum('price');

        $result = User::query();

        $result->select('users.*')->where('is_thai_partner', 1);

        $all_users = $result->orderBy('id', 'desc')->count();

        $users = $result->orderBy('id', 'desc')->get();
        $new_users = [];
        foreach ($users as $key => $user) {
            $n['id'] = $user->id;
            $n['name'] = $user->name;
            $n['last_name'] = $user->last_name;
            $n['price'] = ($sum_withdraw_th / 100) * $user->thai_percent;

            array_push($new_users, $n);
        }

        $users = $new_users;

        return view('th.withdraw_detail_th', compact( 'all_users', 'users'));
    }

    public function addWithDrawTh(Request $request)
    {
        date_default_timezone_set('Asia/Bangkok');
        $date_now = date('Y-m-d H:i:s', time());

        $withdraw = new WithdrawTh();
        $withdraw->price = $request->price;
        $withdraw->updated_at = $date_now;
        $withdraw->created_at = $date_now;
        $withdraw->save();

        return redirect('withdraw_th');
    }
}
