<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Expenditure;
use App\Models\Import_products;
use App\Models\Import_products_ch;
use App\Models\Import_products_th;
use App\Models\Lots_ch;
use App\Models\Price_imports;
use App\Models\Provinces;
use App\Models\Sale_import_ch;
use App\Models\Sale_prices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ImportProductsControllerCh extends Controller
{
  public function index(Request $request)
  {
    $provinces = Provinces::all();
    $districts = Districts::all();
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    // $lots = Lots_ch::all();

    // foreach ($lots as $key => $value) {
    //   $new_lot = ['total_main_price' => $value->total_price];
    //   Lots_ch::where('id', $value->id)->update($new_lot);
    // }

    if (Auth::user()->is_admin == 1) {
      return view('ch_test.import', compact('provinces', 'districts', 'branchs'));
    } else {
      return view('ch_test.importForUser', compact('provinces', 'districts', 'branchs'));
    }
  }

  public function addChinaProduct()
  {

    // $lots = Lots_ch::all();

    // foreach ($lots as $key => $value) {
    //   Import_products_ch::where('lot_id', $value->id)->update([
    //     "receive_branch_id" => $value->receiver_branch_id
    //   ]);
    // }
    return view('ch_test.addChinaProduct');
  }

  public function insertChinaProduct(Request $request)
  {
    if ($request->item_id) {
      $count = 0;
      foreach ($request->item_id as $product_id) {
        $product = new Import_products_ch;
        $product->code = $product_id;
        $product->weight = 0;
        $product->base_price = 0;
        $product->real_price = 0;
        $product->total_base_price = 0;
        $product->total_real_price = 0;
        $product->total_sale_price = 0;
        $product->weight_type = "";
        $product->status = 'waiting';
        $product->receive_branch_id = Auth::user()->branch_id;
        $product->delivery_type = $request->delivery_type[$count];
        $product->addr_detail = $request->addr_detail[$count];

        if ($product->save()) {
        }

        $count++;
      }
      return redirect('addChinaProductCh')->with(['error' => 'insert_success']);
    } else {
      return redirect('addChinaProductCh')->with(['error' => 'not_insert']);
    }
  }

  public function checkImportProduct(Request $request)
  {
    $import_product = Import_products_ch::select('import_products_ch.*')->where('status', 'waiting')->where('code', $request->id)->where('receive_branch_id', $request->receive_branch)->orderBy('import_products_ch.id', 'desc')->first();

    if ($import_product) {
      return response()
        ->json($import_product);
    } else {
      return response()
        ->json(['error' => '1']);
    }
  }

  public function importProduct(Request $request)
  {
    if ($request->item_id) {

      $sum_price = 0;
      $sum_m_weight = 0;
      $count = 0;
      foreach ($request->weight_type as $weight_type) {
        if ($weight_type == 'm') {
          $sum_m_weight += $request->weight[$count];
        } else {
          if ($request->weight_kg <= 0) {
            return redirect('importCh')->with(['error' => 'not_insert']);
          }
        }

        $count++;
      }

      $default_price_kg = Price_imports::where('weight_type', 'kg')
        ->orderBy('id', 'DESC')->first();

      $default_price_m = Price_imports::where('weight_type', 'm')
        ->orderBy('id', 'DESC')->first();

      $sum_kg_base_price = ($request->base_price_kg == '' ? $default_price_kg->base_price : $request->base_price_kg) * $request->weight_kg;
      $sum_m_base_price = ($request->base_price_m == '' ? $default_price_m->base_price : $request->base_price_m) * $sum_m_weight;
      $sum_base_price = $sum_m_base_price + $sum_kg_base_price;

      $sum_kg_price = ($request->real_price_kg == '' ? $default_price_kg->real_price : $request->real_price_kg) * $request->weight_kg;
      $sum_m_price = ($request->real_price_m == '' ? $default_price_m->real_price : $request->real_price_m) * $sum_m_weight;
      $sum_price = $sum_m_price + $sum_kg_price;

      $lot = new Lots_ch;
      $lot->receiver_branch_id = $request->receiver_branch_id;
      $lot->weight_kg = $request->weight_kg;
      $lot->total_base_price_kg = $sum_kg_base_price;
      $lot->total_base_price_m = $sum_m_base_price;
      $lot->total_base_price = $sum_base_price;
      $lot->total_main_price = $sum_price + $request->fee + $request->pack_price;
      $lot->total_price = $sum_price;
      $lot->total_unit_m = $sum_m_price;
      $lot->total_unit_kg = $sum_kg_price;
      $lot->status = 'sending';
      $lot->payment_status = 'not_paid';
      $lot->fee = $request->fee;
      $lot->pack_price = $request->pack_price;
      $lot->lot_real_price_kg = $request->real_price_kg;
      $lot->lot_base_price_kg = $request->base_price_kg;
      $lot->lot_real_price_m = $request->real_price_m;
      $lot->lot_base_price_m = $request->base_price_m;

      if ($lot->save()) {
        $count = 0;
        foreach ($request->item_id as $product_id) {
          $price = Price_imports::where('weight_type', $request->weight_type[$count])
            ->orderBy('id', 'DESC')->first();

          $product = array();
          // $product->code = $product_id;

          if ($request->weight_type[$count] == 'm') {
            $product["weight"] = $request->weight[$count];
            $product["base_price"] = $request->base_price_m == '' ? $price->base_price : $request->base_price_m;
            $product["real_price"] = $request->real_price_m == '' ? $price->real_price : $request->real_price_m;
            $product["total_base_price"] = ($request->base_price_m == '' ? $price->base_price : $request->base_price_m) * $request->weight[$count];
            $product["total_real_price"] = ($request->real_price_m == '' ? $price->real_price : $request->real_price_m) * $request->weight[$count];
            $product["total_sale_price"] = 0;
          } else {
            $product["weight"] = 0;
            $product["base_price"] = $request->base_price_kg == '' ? $price->base_price : $request->base_price_kg;
            $product["real_price"] = $request->real_price_kg == '' ? $price->real_price : $request->real_price_kg;
            $product["total_base_price"] = 0;
            $product["total_real_price"] = 0;
            $product["total_sale_price"] = 0;
          }

          $product["weight_type"] = $request->weight_type[$count];
          $product["status"] = 'sending';
          $product["lot_id"] = $lot->id;

          Import_products_ch::where('code', $product_id)
            ->update($product);

          $count++;
        }
        return redirect('importCh')->with(['error' => 'insert_success', 'id' => $lot->id]);
      } else {
        return redirect('importCh')->with(['error' => 'not_insert']);
      }
    } else {
      return redirect('importCh')->with(['error' => 'not_insert']);
    }
  }

  public function insertImportForUser(Request $request)
  {
    if ($request->item_id) {
      foreach ($request->item_id as $product_code) {

        $import_product = Import_products_ch::where('id', $product_code)
          ->orderBy('id', 'DESC')->first();

        $new_import_product_update =  [
          'received_at' => Carbon::now(),
          'status' => 'received',
        ];

        if (Import_products_ch::where('id', $import_product->id)->update($new_import_product_update)) {
          $count_status = Import_products_ch::where('status', 'sending')->where('lot_id', $import_product->lot_id)->count();
          if ($count_status < 1) {
            Lots_ch::where('id', $import_product->lot_id)->update(['status' => 'received']);
          } else {
            Lots_ch::where('id', $import_product->lot_id)->update(['status' => 'not_full']);
          }
        }
      }

      return redirect('importCh')->with(['error' => 'insert_success']);
    } else {
      return redirect('importCh')->with(['error' => 'not_insert']);
    }
  }

  public function insertSaleImportForRider(Request $request)
  {
    if ($request->items) {
      $sum_price = 0;

      foreach ($request->items as $key => $value) {
        $sum_price += ($value["price"] * $value["weight"]) + ($value["shipping_fee"] * $value["weight"]);
      }

      $sale_import = new Sale_import_ch;
      $sale_import->branch_id = Auth::user()->branch_id;
      $sale_import->total = $sum_price - ($request->discount == "" ? 0 : $request->discount);
      $sale_import->discount = $request->discount == "" ? 0 : $request->discount;
      $sale_import->subtotal = $sum_price;
      $sale_import->sale_type = "tohouse";

      if ($sale_import->save()) {
        foreach ($request->items as $key => $value) {

          $import_product = Import_products_ch::where('id', $value["id"])
            ->orderBy('id', 'DESC')->first();

          $lot = Lots_ch::where('id', $import_product->lot_id)
            ->orderBy('id', 'DESC')->first();

          if ($import_product->weight_type != 'm') {
            $new_import_product_update =  [
              'status' => 'success',
              'success_at' => Carbon::now(),
              'sale_id' => $sale_import->id,
              'weight' => $value["weight"],
              'shipping_fee' => $value["shipping_fee"] * $value["weight"],
              'sale_price' => $value["price"],
              'total_base_price' => ($lot->total_base_price_kg / $lot->weight_kg) * $value["weight"],
              'total_real_price' => ($lot->total_unit_kg / $lot->weight_kg) * $value["weight"],
              'total_sale_price' => ($value["price"] * $value["weight"])
            ];
          } else {
            $new_import_product_update =  [
              'status' => 'success',
              'success_at' => Carbon::now(),
              'sale_id' => $sale_import->id,
              'sale_price' => $value["price"],
              'shipping_fee' => $value["shipping_fee"] * $value["weight"],
              'weight' => $value["weight"],
              'total_sale_price' => ($value["price"] * $value["weight"])
            ];
          }

          if (Import_products_ch::where('id', $value["id"])->update($new_import_product_update)) {

            $import_product = Import_products_ch::where('id', $value["id"])
              ->orderBy('id', 'DESC')->first();

            $count_status = Import_products_ch::where('status', 'success')->where('lot_id', $import_product->lot_id)->get();
            $all = Import_products_ch::where('lot_id', $import_product->lot_id)->get();

            $sum_sale_price = Import_products_ch::where('lot_id', $import_product->lot_id)->where('status', 'success')->sum('total_sale_price');
            Lots_ch::where('id', $import_product->lot_id)->update(['total_sale_price' => $sum_sale_price]);

            if ($count_status == $all) {
              Lots_ch::where('id', $import_product->lot_id)->update(['status' => 'success']);
            }
          } else {
            Import_products_ch::where('sale_id', $sale_import->id)
              ->where('weight_type', 'kg')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'weight' => '',
                'total_sale_price' =>  '',
                'shipping_fee' => null
              ]);

            Import_products_ch::where('sale_id', $sale_import->id)
              ->where('weight_type', 'm')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'total_sale_price' =>  '',
                'shipping_fee' => null
              ]);
            $sale = Sale_import_ch::where('id', $sale_import->id);
            $sale->delete();
            return response()
              ->json(['id' => 0]);
          }
        }
        return response()
          ->json(['id' => $sale_import->id]);
        // return redirect('saleImport')->with(['error' => 'insert_success', 'id' => $sale_import->id]);
      } else {
        return response()
          ->json(['id' => 0]);
      }
    } else {
      return response()
        ->json(['id' => 0]);
    }
  }


  public function importView(Request $request)
  {

    // $prods = Import_products_ch::distinct()->get('lot_id');
    // foreach ($prods as $key => $value) {
    //   $prod_m = Import_products_ch::where('weight_type', 'm')->where('lot_id', $value->lot_id)
    //     ->first();

    //   if ($prod_m) {
    //     Lots_ch::where('id', $value->lot_id)->update([
    //       'lot_base_price_m' => $prod_m->base_price,
    //       'lot_real_price_m' => $prod_m->real_price,
    //     ]);
    //   }

    //   $prod_kg = Import_products_ch::where('weight_type', 'kg')->where('lot_id', $value->lot_id)
    //     ->first();

    //   if ($prod_kg) {
    //     Lots_ch::where('id', $value->lot_id)->update([
    //       'lot_base_price_kg' => $prod_kg->base_price,
    //       'lot_real_price_kg' => $prod_kg->real_price,
    //     ]);
    //   }
    // }


    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();
    $result = Lots_ch::query();

    $result->select(
      'lot_ch.*',
      'receive.branch_name as receiver_branch_name'
    )
      ->join('branchs As receive', 'lot_ch.receiver_branch_id', 'receive.id');

    // if (Auth::user()->is_admin != '1') {
    //   $result->where('import_products_ch.sender_branch_id', Auth::user()->branch_id);
    // }

    if ($request->send_date != '') {
      $result->whereDate('lot_ch.created_at', '=',  $request->send_date);
    }
    if ($request->id != '') {
      $result->where('lot_ch.id', $request->id);
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

    $all_lots = $result->orderBy('lot_ch.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot_ch.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_lots / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_lots
    ];

    return view('ch_test.importView', compact('branchs', 'lots', 'pagination'));
  }

  public function importViewForUser(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();
    $result = Lots_ch::query();

    $result->select(
      'lot_ch.*',
      'receive.branch_name as receiver_branch_name'
    )
      ->join('branchs As receive', 'lot_ch.receiver_branch_id', 'receive.id')
      ->where('receiver_branch_id', Auth::user()->branch_id);

    // if (Auth::user()->is_admin != '1') {
    //   $result->where('import_products_ch.sender_branch_id', Auth::user()->branch_id);
    // }

    if ($request->send_date != '') {
      $result->whereDate('lot_ch.created_at', '=',  $request->send_date);
    }
    if ($request->id != '') {
      $result->where('lot_ch.id', $request->id);
    }
    if ($request->status != '') {
      $result->where('status', $request->status);
    }

    if ($request->receive_branch != '') {
      $result->where('receiver_branch_id', $request->receive_branch);
    }

    $all_lots = $result->orderBy('lot_ch.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot_ch.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_lots / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_lots
    ];

    return view('ch_test.importView', compact('branchs', 'lots', 'pagination'));
  }

  public function report($id)
  {
    $lot = Lots_ch::find($id);
    $receive_branch = Branchs::find($lot->receiver_branch_id);

    $data = [
      'id' => $lot->id,
      'date' => date('d-m-Y', strtotime($lot->created_at)),
      'to' => $receive_branch->branch_name,
      'weight_kg' => $lot->weight_kg,
      'price' => $lot->total_main_price,
      'pack_price' => $lot->pack_price,
      'fee' => $lot->fee,
    ];
    $pdf = PDF::loadView('pdf.import', $data);
    return $pdf->stream('document.pdf');
  }

  public function importDetail(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products_ch::query();

    $result->select('import_products_ch.*')
      ->where('lot_id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('import_products_ch.created_at', '=',  $request->send_date);
    }

    if ($request->product_id != '') {
      $result->where('import_products_ch.code', $request->product_id);
    }

    if ($request->status != '') {
      $result->where('status', $request->status);
    }

    if ($request->receive_branch != '') {
      $result->where('receiver_branch_id', $request->receive_branch);
    }

    $all_import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    // echo ($import_products));
    // exit;

    return view('ch_test.importDetail', compact('branchs', 'import_products', 'pagination'));
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

    $result = DB::table('lot_ch')
      ->select(DB::raw('branchs.id as receiver_branch_id, branchs.branch_name as branch_name, sum(lot_ch.total_price) as branch_total_price'))
      // ->join('import_products', 'lot_ch.id', 'import_products_ch.lot_id')
      ->join('branchs', 'lot_ch.receiver_branch_id', 'branchs.id')
      ->whereBetween('lot_ch.created_at', [$date, $to_date])
      ->groupBy('branchs.id')
      ->groupBy('branchs.branch_name');

    if (Auth::user()->branch_id == null) {

      $sum_base_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->sum("total_base_price");
      $sum_real_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->sum("total_main_price");

      $sum_fee_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->sum("fee");
      $sum_pack_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->sum("pack_price");
    } else {

      $result->where('lot_ch.receiver_branch_id', Auth::user()->branch_id);

      $sum_base_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->where('lot_ch.receiver_branch_id', Auth::user()->branch_id)
        ->sum("total_main_price");
      $sum_real_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->where('lot_ch.receiver_branch_id', Auth::user()->branch_id)
        ->sum("total_sale_price");

      $sum_fee_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->where('lot_ch.receiver_branch_id', Auth::user()->branch_id)
        ->sum("fee");
      $sum_pack_price = Lots_ch::whereBetween('lot_ch.created_at', [$date, $to_date])
        ->where('lot_ch.receiver_branch_id', Auth::user()->branch_id)
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

    $import_product_count = DB::table('lot_ch')
      ->select(DB::raw('count(import_products_ch.id) as count_import_product, lot_ch.receiver_branch_id'))
      ->join('import_products_ch', 'lot_ch.id', 'import_products_ch.lot_id')
      ->join('branchs', 'lot_ch.receiver_branch_id', 'branchs.id')
      ->whereBetween('lot_ch.created_at', [$date, $to_date])
      ->groupBy('lot_ch.receiver_branch_id')->get();

    $result_unpaid = DB::table('lot_ch')
      ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot_ch.total_main_price) as branch_total_price'))
      // ->join('import_products_ch', 'lot_ch.id', 'import_products_ch.lot_id')
      ->join('branchs', 'lot_ch.receiver_branch_id', 'branchs.id')
      ->whereBetween('lot_ch.created_at', [$date, $to_date])
      ->where('lot_ch.payment_status', 'not_paid')
      ->groupBy('branchs.id')
      ->groupBy('branchs.branch_name')
      ->get();

    $result_paid = DB::table('lot_ch')
      ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot_ch.total_main_price) as branch_total_price'))
      // ->join('import_products_ch', 'lot_ch.id', 'import_products_ch.lot_id')
      ->join('branchs', 'lot_ch.receiver_branch_id', 'branchs.id')
      ->whereBetween('lot_ch.created_at', [$date, $to_date])
      ->where('lot_ch.payment_status', '<>', 'not_paid')
      ->groupBy('branchs.id')
      ->groupBy('branchs.branch_name')
      ->get();

    $result_weight = DB::table('lot_ch')
      ->select(DB::raw('branchs.id as receiver_branch_id, sum(lot_ch.weight_kg) as sum_weight_kg'))
      // ->join('import_products_ch', 'lot_ch.id', 'import_products_ch.lot_id')
      ->join('branchs', 'lot_ch.receiver_branch_id', 'branchs.id')
      ->whereBetween('lot_ch.created_at', [$date, $to_date])
      ->groupBy('branchs.id')
      ->groupBy('branchs.branch_name')
      ->get();

    $result_weight_m = DB::table('lot_ch')
      ->select(DB::raw('branchs.id as receiver_branch_id, sum(import_products_ch.weight) as sum_weight_m'))
      // ->join('import_products_ch', 'lot_ch.id', 'import_products_ch.lot_id')
      ->join('branchs', 'lot_ch.receiver_branch_id', 'branchs.id')
      ->join('import_products_ch', 'lot_ch.id', 'import_products_ch.lot_id')
      ->whereBetween('lot_ch.created_at', [$date, $to_date])
      ->where('import_products_ch.weight_type', 'm')
      ->groupBy('branchs.id')
      ->groupBy('branchs.branch_name')
      ->get();


    // print_r($result_paid);
    // exit;

    $sum_share = 0;
    if (Auth::user()->is_admin != 1 && Auth::user()->branch_id == null) {
      $sum_share = $sum_profit / Auth::user()->percent;
    }

    return view('ch_test.dailyimport', compact('sum_base_price', 'sum_real_price', 'sum_sale_profit', 'sum_profit', 'sum_expenditure', 'date_now', 'branch_sale_totals', 'pagination', 'to_date_now', 'import_product_count', 'result_paid', 'result_unpaid', 'sum_fee_price', 'sum_pack_price', 'sum_share', 'result_weight', 'result_weight_m'));
  }

  public function importDetailForUser(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products_ch::query();

    $result->select('import_products_ch.*')
      ->join('lot_ch', 'lot_ch.id', 'import_products_ch.lot_id')
      ->join('branchs As receive', 'lot_ch.receiver_branch_id', 'receive.id')
      ->where('lot_id', $request->id)
      ->where('lot_ch.receiver_branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('import_products_ch.created_at', '=',  $request->send_date);
    }

    if ($request->product_id != '') {
      $result->where('import_products_ch.code', $request->product_id);
    }

    if ($request->status != '') {
      $result->where('import_products_ch.status', $request->status);
    }

    if ($request->receive_branch != '') {
      $result->where('receiver_branch_id', $request->receive_branch);
    }

    $all_import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('ch_test.importDetailForUser', compact('branchs', 'import_products', 'pagination'));
  }


  public function importProductTrack(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products_ch::query();

    $result->select('import_products_ch.*', 'receive.branch_name')
      ->join('branchs As receive', 'import_products_ch.receive_branch_id', 'receive.id');

    if ($request->send_date != '') {
      $result->whereDate('import_products_ch.created_at', '=',  $request->send_date);
    }

    if ($request->product_id != '') {
      $result->where('import_products_ch.code', $request->product_id);
    }

    if ($request->status != '') {
      $result->where('import_products_ch.status', $request->status);
    }

    if ($request->receive_branch != '') {
      $result->where('receiver_branch_id', $request->receive_branch);
    }

    $all_import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('ch_test.allImportDetail', compact('branchs', 'import_products', 'pagination'));
  }

  public function importProductTrackForUser(Request $request)
  {

    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products_ch::query();

    $result->select('import_products_ch.*', 'receive.branch_name')
      ->join('branchs As receive', 'import_products_ch.receive_branch_id', 'receive.id');

    if ($request->send_date != '') {
      $result->whereDate('import_products_ch.received_at', '=',  $request->send_date);
    }

    if ($request->product_id != '') {
      $result->where('import_products_ch.code', $request->product_id);
    }

    if ($request->status != '') {
      $result->where('import_products_ch.status', $request->status);
    }

    if ($request->receive_branch != '') {
      $result->where('receive_branch_id', $request->receive_branch);
    }

    $all_import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_ch.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('ch_test.allImportDetailForUser', compact('import_products', 'pagination', 'branchs'));
  }

  public function getImportProduct(Request $request)
  {

    $import_product = Import_products_ch::select('import_products_ch.*')->join('lot_ch', 'lot_ch.id', 'import_products_ch.lot_id')->where('code', $request->id)->where('lot_ch.receiver_branch_id', Auth::user()->branch_id)->orderBy('import_products_ch.id', 'desc')->first();

    if ($import_product) {
      return response()
        ->json($import_product);
    } else {
      return response()
        ->json(['error' => '1']);
    }
  }

  public function deleteImportItem(Request $request)
  {
    $lot = Lots_ch::where('id', $request->lot_id)
      ->orderBy('id', 'DESC')->first();

    Lots_ch::where('id', $request->lot_id)->update(
      [
        'total_base_price' => ($lot->total_base_price - ($request->base_price * $request->weight)),
        'total_price' => ($lot->total_price - ($request->real_price * $request->weight)),
        'weight_kg' => $lot->weight_kg - ($request->weight_type == 'm' ? 0 : $request->weight),
      ]
    );

    $import_product = Import_products_ch::where('id', $request->lot_item_id);
    $import_product->delete();

    $count_import_product = Import_products_ch::where('lot_id', $request->lot_id)->count();
    if ($count_import_product < 1) {
      $lot->delete();
    }

    return redirect('importDetailCh?id=' . $request->lot_id)->with(['error' => 'insert_success']);
  }

  public function changeImportItemWeight(Request $request)
  {

    $import_product = Import_products_ch::where('id', $request->lot_item_id_in_weight)->first();

    $lot = Lots_ch::where('id', $request->lot_id_in_weight)
      ->orderBy('id', 'DESC')->first();

    Lots_ch::where('id', $request->lot_id_in_weight)->update(
      [
        'total_base_price' => (($lot->total_base_price - ($import_product->base_price * $import_product->weight)) + ($request->base_price_in_weight * $request->weight_in_weight)),
        'total_price' => (($lot->total_price - ($import_product->real_price * $import_product->weight)) + ($request->real_price_in_weight * $request->weight_in_weight)),
        'total_main_price' => (($lot->total_price - ($import_product->real_price * $import_product->weight)) + ($request->real_price_in_weight * $request->weight_in_weight) + ($lot->fee ? $lot->fee : 0) + ($lot->pack_price ? $lot->pack_price : 0)),
      ]
    );

    $import_product = Import_products_ch::where('id', $request->lot_item_id_in_weight)->update(
      [
        'weight' => $request->weight_in_weight,
      ]
    );

    return redirect('importDetailCh?id=' . $request->lot_id_in_weight)->with(['error' => 'insert_success']);
  }

  public function deleteLot(Request $request)
  {
    $lot = lots_ch::where('id', $request->id);
    $lot->delete();
    $import_products = Import_products_ch::where('lot_id', $request->id);
    $import_products->delete();
    return redirect('importViewCh')->with(['error' => 'delete_success']);
  }

  public function paidLot(Request $request)
  {
    $lot = lots_ch::where('id', $request->id)->update(
      [
        'payment_status' => 'paid'
      ]
    );
    return redirect('importViewCh')->with(['error' => 'insert_success']);
  }

  public function changeImportWeight(Request $request)
  {

    $base_price_kg = $request->lot_base_price_kg ? $request->lot_base_price_kg : 0;
    $real_price_kg = $request->lot_real_price_kg ? $request->lot_real_price_kg : 0;
    $base_price_m = $request->lot_base_price_m ? $request->lot_base_price_m : 0;
    $real_price_m = $request->lot_real_price_m ? $request->lot_real_price_m : 0;
    $weight_m = Import_products_ch::where('lot_id', $request->lot_id_in_weight)
      ->where('weight_type', 'm')
      ->sum('weight');

    Lots_ch::where('id', $request->lot_id_in_weight)->update(
      [
        'weight_kg' => $request->weight_in_weight,
        'total_base_price_kg' => $base_price_kg * $request->weight_in_weight,
        'total_unit_kg' => $real_price_kg * $request->weight_in_weight,
        'total_base_price' => (($base_price_kg * $request->weight_in_weight) + ($weight_m * $base_price_m)),
        'total_price' => (($real_price_kg * $request->weight_in_weight) + ($weight_m * $real_price_m)),
        'total_main_price' => (($real_price_kg * $request->weight_in_weight) + ($weight_m * $real_price_m) + $request->fee + $request->pack_price),
        'lot_base_price_kg' => $base_price_kg,
        'lot_real_price_kg' => $real_price_kg,
        'lot_base_price_m' => $base_price_m,
        'lot_real_price_m' => $real_price_m,
      ]
    );

    return redirect('importViewCh')->with(['error' => 'insert_success']);
  }
}
