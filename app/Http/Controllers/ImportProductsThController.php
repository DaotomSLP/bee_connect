<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\import_products_th;
use App\Models\Lots;
use App\Models\Lots_th;
use App\Models\Price;
use App\Models\Price_imports;
use App\Models\Price_imports_th;
use App\Models\Provinces;
use App\Models\Sale_import;
use App\Models\Sale_import_th;
use App\Models\Sale_prices;
use Carbon\Carbon;
use DateTime;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Ui\Presets\React;
use PDF;

class ImportProductsThController extends Controller
{
  public function index(Request $request)
  {
    $provinces = Provinces::all();
    $districts = Districts::all();
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    // $lots = Lots_th::all();

    // foreach ($lots as $key => $value) {
    //   $new_lot = ['total_main_price' => $value->total_price];
    //   Lots_th::where('id', $value->id)->update($new_lot);
    // }

    if (Auth::user()->is_admin == 1) {
      return view('importTh', compact('provinces', 'districts', 'branchs'));
    } else {
      return view('importForUserTh', compact('provinces', 'districts', 'branchs'));
    }
  }

  public function addImportTh()
  {
    $provinces = Provinces::all();
    $districts = Districts::all();
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    return view('addImportTh', compact('provinces', 'districts', 'branchs'));
  }

  public function addImportProductTh(Request $request)
  {
    $imp_th_id = import_products_th::select('id')->orderBy('id', 'desc')->first();
    $import_products_th = new import_products_th;
    $import_products_th->name = $request->name;
    $import_products_th->detail = $request->detail;
    $import_products_th->receive_branch_id = $request->receiver_branch_id;
    $import_products_th->code = $imp_th_id ? $imp_th_id['id'] + 1 : 10000000;
    $import_products_th->status = 'waiting';

    if ($import_products_th->save()) {
      return redirect('addImportTh')->with(['error' => 'insert_success']);
    } else {
      return redirect('addImportTh')->with(['error' => 'not_success']);
    }
  }

  public function saleImportTh(Request $request)
  {
    $sale_price_gram = Sale_prices::where('weight_type', 'gram')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();


    $sale_price_m = Sale_prices::where('weight_type', 'm')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();

    return view('saleImportTh', compact('sale_price_gram', 'sale_price_m'));
  }

  public function addChinaProduct()
  {

    // $lots = Lots_th::all();

    // foreach ($lots as $key => $value) {
    //   import_products_th::where('lot_id', $value->id)->update([
    //     "receive_branch_id" => $value->receiver_branch_id
    //   ]);
    // }
    return view('addChinaProduct');
  }

  public function insertChinaProduct(Request $request)
  {
    if ($request->item_id) {
      $count = 0;
      foreach ($request->item_id as $product_id) {
        $product = new Import_products;
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
      return redirect('import')->with(['error' => 'insert_success']);
    } else {
      return redirect('import')->with(['error' => 'not_insert']);
    }
  }

  public function checkImportProductTh(Request $request)
  {
    $import_product_th = import_products_th::select('import_products_th.*')->where('status', 'waiting')->where('code', $request->id)->where('receive_branch_id', $request->receive_branch)->orderBy('import_products_th.id', 'desc')->first();

    if ($import_product_th) {
      return response()
        ->json($import_product_th);
    } else {
      return response()
        ->json(['error' => '1']);
    }
  }

  public function importProductTh(Request $request)
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
            return redirect('importTh')->with(['error' => 'not_insert']);
          }
        }

        $count++;
      }

      $default_price_kg = Price_imports::where('weight_type', 'gram')
        ->orderBy('id', 'DESC')->first();

      $default_price_m = Price_imports::where('weight_type', 'm')
        ->orderBy('id', 'DESC')->first();

      $sum_kg_base_price = ($request->base_price_kg == '' ? $default_price_kg->base_price : $request->base_price_kg) * $request->weight_kg;
      $sum_m_base_price = ($request->base_price_m == '' ? $default_price_m->base_price : $request->base_price_m) * $sum_m_weight;
      $sum_base_price = $sum_m_base_price + $sum_kg_base_price;

      $sum_kg_price = ($request->real_price_kg == '' ? $default_price_kg->real_price : $request->real_price_kg) * $request->weight_kg;
      $sum_m_price = ($request->real_price_m == '' ? $default_price_m->real_price : $request->real_price_m) * $sum_m_weight;
      $sum_price = $sum_m_price + $sum_kg_price;

      $lot = new Lots_th;
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
          $price = Price_imports_th::where('weight_type', $request->weight_type[$count])
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

          import_products_th::where('code', $product_id)
            ->update($product);

          $count++;
        }
        return redirect('importTh')->with(['error' => 'insert_success', 'id' => $lot->id]);
      } else {
        return redirect('importTh')->with(['error' => 'not_insert']);
      }
    } else {
      return redirect('import')->with(['error' => 'not_insert']);
    }
  }

  public function insertImportForUserTh(Request $request)
  {
    if ($request->item_id) {
      $count = 0;
      foreach ($request->item_id as $product_code) {

        $import_product = import_products_th::where('id', $product_code)
          ->orderBy('id', 'DESC')->first();

        $new_import_product_update =  [
          'received_at' => Carbon::now(),
          'status' => 'received',
        ];

        if (import_products_th::where('id', $import_product->id)->update($new_import_product_update)) {
          $count_status = import_products_th::where('status', 'sending')->where('lot_id', $import_product->lot_id)->count();
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
    if ($request->items) {
      $sum_price = 0;

      foreach ($request->items as $key => $value) {
        $sum_price += ($value["price"] * $value["weight"]);
      }

      $sale_import = new Sale_import_th;
      $sale_import->branch_id = Auth::user()->branch_id;
      $sale_import->total = $sum_price - ($request->discount == "" ? 0 : $request->discount);
      $sale_import->discount = $request->discount == "" ? 0 : $request->discount;
      $sale_import->subtotal = $sum_price;
      $sale_import->sale_type = "normal";

      if ($sale_import->save()) {
        foreach ($request->items as $key => $value) {

          $import_product = import_products_th::where('id', $value["id"])
            ->orderBy('id', 'DESC')->first();

          $lot = Lots_th::where('id', $import_product->lot_id)
            ->orderBy('id', 'DESC')->first();

          if ($import_product->weight_type != 'm') {
            $new_import_product_update =  [
              'status' => 'success',
              'success_at' => Carbon::now(),
              'sale_id' => $sale_import->id,
              'weight' => $value["weight"],
              'shipping_fee' => 0,
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
              'shipping_fee' => 0,
              'weight' => $value["weight"],
              'total_sale_price' => ($value["price"] * $value["weight"])
            ];
          }

          if (import_products_th::where('id', $value["id"])->update($new_import_product_update)) {

            $import_product = import_products_th::where('id', $value["id"])
              ->orderBy('id', 'DESC')->first();

            $count_status = import_products_th::where('status', 'success')->where('lot_id', $import_product->lot_id)->get();
            $all = import_products_th::where('lot_id', $import_product->lot_id)->get();

            $sum_sale_price = import_products_th::where('lot_id', $import_product->lot_id)->where('status', 'success')->sum('total_sale_price');
            Lots_th::where('id', $import_product->lot_id)->update(['total_sale_price' => $sum_sale_price]);

            if ($count_status == $all) {
              Lots_th::where('id', $import_product->lot_id)->update(['status' => 'success']);
            }
          } else {
            import_products_th::where('sale_id', $sale_import->id)
              ->where('weight_type', 'gram')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'weight' => '',
                'total_sale_price' =>  '',
                'shipping_fee' => null
              ]);

            import_products_th::where('sale_id', $sale_import->id)
              ->where('weight_type', 'm')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'total_sale_price' =>  '',
                'shipping_fee' => null
              ]);
            $sale = Sale_import_th::where('id', $sale_import->id);
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

  public function insertSaleImportForRiderTh(Request $request)
  {
    if ($request->items) {
      $sum_price = 0;

      foreach ($request->items as $key => $value) {
        $sum_price += ($value["price"] * $value["weight"]) + ($value["shipping_fee"] * $value["weight"]);
      }

      $sale_import = new Sale_import_th;
      $sale_import->branch_id = Auth::user()->branch_id;
      $sale_import->total = $sum_price - ($request->discount == "" ? 0 : $request->discount);
      $sale_import->discount = $request->discount == "" ? 0 : $request->discount;
      $sale_import->subtotal = $sum_price;
      $sale_import->sale_type = "tohouse";

      if ($sale_import->save()) {
        foreach ($request->items as $key => $value) {

          $import_product = import_products_th::where('id', $value["id"])
            ->orderBy('id', 'DESC')->first();

          $lot = Lots_th::where('id', $import_product->lot_id)
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

          if (import_products_th::where('id', $value["id"])->update($new_import_product_update)) {

            $import_product = import_products_th::where('id', $value["id"])
              ->orderBy('id', 'DESC')->first();

            $count_status = import_products_th::where('status', 'success')->where('lot_id', $import_product->lot_id)->get();
            $all = import_products_th::where('lot_id', $import_product->lot_id)->get();

            $sum_sale_price = import_products_th::where('lot_id', $import_product->lot_id)->where('status', 'success')->sum('total_sale_price');
            Lots_th::where('id', $import_product->lot_id)->update(['total_sale_price' => $sum_sale_price]);

            if ($count_status == $all) {
              Lots_th::where('id', $import_product->lot_id)->update(['status' => 'success']);
            }
          } else {
            import_products_th::where('sale_id', $sale_import->id)
              ->where('weight_type', 'gram')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'weight' => '',
                'total_sale_price' =>  '',
                'shipping_fee' => null
              ]);

            import_products_th::where('sale_id', $sale_import->id)
              ->where('weight_type', 'm')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'total_sale_price' =>  '',
                'shipping_fee' => null
              ]);
            $sale = Sale_import_th::where('id', $sale_import->id);
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

  public function importViewTh(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();
    $result = Lots_th::query();

    $result->select(
      'lot_th.*',
      'receive.branch_name as receiver_branch_name'
    )
      ->join('branchs As receive', 'lot_th.receiver_branch_id', 'receive.id');

    // if (Auth::user()->is_admin != '1') {
    //   $result->where('import_products_th.sender_branch_id', Auth::user()->branch_id);
    // }

    if ($request->send_date != '') {
      $result->whereDate('lot_th.created_at', '=',  $request->send_date);
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

    $all_lots = $result->orderBy('lot_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_lots / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_lots
    ];

    return view('importViewTh', compact('branchs', 'lots', 'pagination'));
  }

  public function saleViewTh(Request $request)
  {
    $result = Sale_import_th::query();

    $result->select('sale_import_th.*')->where('branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import_th.created_at', '=', $request->send_date);
    }
    if ($request->id != '') {
      $result->where('sale_import_th.id', $request->id);
    }

    $all_sale_imports = $result->orderBy('sale_import_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $sale_imports = $result->orderBy('sale_import_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_sale_imports / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_sale_imports
    ];

    return view('saleViewTh', compact('sale_imports', 'pagination'));
  }

  public function importViewForUserTh(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();
    $result = Lots_th::query();

    $result->select(
      'lot_th.*',
      'receive.branch_name as receiver_branch_name'
    )
      ->join('branchs As receive', 'lot_th.receiver_branch_id', 'receive.id')
      ->where('receiver_branch_id', Auth::user()->branch_id);

    // if (Auth::user()->is_admin != '1') {
    //   $result->where('import_products_th.sender_branch_id', Auth::user()->branch_id);
    // }

    if ($request->send_date != '') {
      $result->whereDate('lot_th.created_at', '=',  $request->send_date);
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

    $all_lots = $result->orderBy('lot_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_lots / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_lots
    ];

    return view('importViewTh', compact('branchs', 'lots', 'pagination'));
  }

  public function reportTh($id)
  {
    $lot = Lots_th::find($id);
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
      'items' => $items
    ];

    $pdf = PDF::loadView('pdf.sale', $data);
    return $pdf->stream('document.pdf');
  }

  public function importDetailTh(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = import_products_th::query();

    $result->select('import_products_th.*')
      ->where('lot_id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('import_products_th.created_at', '=',  $request->send_date);
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

    $all_import_products = $result->orderBy('import_products_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    // echo ($import_products));
    // exit;

    return view('importDetailTh', compact('branchs', 'import_products', 'pagination'));
  }

  public function saleDetailTh(Request $request)
  {

    $result = import_products_th::query();

    $result->select('import_products_th.*')
      ->join('sale_import', 'sale_import_th.id', 'import_products_th.sale_id')
      ->where('sale_import_th.id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import_th.created_at', '=',  $request->send_date);
    }

    if ($request->product_id != '') {
      $result->where('import_products_th.code', $request->product_id);
    }

    $all_import_products = $result->orderBy('sale_import_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('saleDetailTh', compact('import_products', 'pagination'));
  }

  public function importDetailForUser(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = import_products_th::query();

    $result->select('import_products_th.*')
      ->join('lot', 'lot_th.id', 'import_products_th.lot_id')
      ->join('branchs As receive', 'lot_th.receiver_branch_id', 'receive.id')
      ->where('lot_id', $request->id)
      ->where('lot_th.receiver_branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('import_products_th.created_at', '=',  $request->send_date);
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

    $all_import_products = $result->orderBy('import_products_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('importDetailForUser', compact('branchs', 'import_products', 'pagination'));
  }


  public function importProductTrackTh(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products_th::query();

    $result->select('import_products_th.*', 'receive.branch_name')
      ->join('lot_th', 'lot_th.id', 'import_products_th.lot_id')
      ->join('branchs As receive', 'lot_th.receiver_branch_id', 'receive.id');

    if ($request->send_date != '') {
      $result->whereDate('import_products_th.created_at', '=',  $request->send_date);
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

    $all_import_products = $result->orderBy('import_products_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('allImportDetailTh', compact('branchs', 'import_products', 'pagination'));
  }

  public function importProductTrackForUserTh(Request $request)
  {

    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = import_products_th::query();

    $result->select('import_products_th.*', 'receive.branch_name')
      ->join('branchs As receive', 'import_products_th.receive_branch_id', 'receive.id');

    if ($request->send_date != '') {
      $result->whereDate('import_products_th.received_at', '=',  $request->send_date);
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

    $all_import_products = $result->orderBy('import_products_th.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products_th.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('allImportDetailForUserTh', compact('import_products', 'pagination', 'branchs'));
  }

  public function getImportProductTh(Request $request)
  {

    $import_product = import_products_th::select('import_products_th.*')->join('lot_th', 'lot_th.id', 'import_products_th.lot_id')->where('code', $request->id)->where('lot_th.receiver_branch_id', Auth::user()->branch_id)->orderBy('import_products_th.id', 'desc')->first();

    if ($import_product) {
      return response()
        ->json($import_product);
    } else {
      return response()
        ->json(['error' => '1']);
    }
  }

  public function deleteImportItemTh(Request $request)
  {
    $lot = Lots_th::where('id', $request->lot_id)
      ->orderBy('id', 'DESC')->first();

    Lots_th::where('id', $request->lot_id)->update(
      [
        'total_base_price' => ($lot->total_base_price - ($request->base_price * $request->weight)),
        'total_price' => ($lot->total_price - ($request->real_price * $request->weight)),
        'weight_kg' => $lot->weight_kg - ($request->weight_type == 'm' ? 0 : $request->weight),
      ]
    );

    $import_product = import_products_th::where('id', $request->lot_item_id);
    $import_product->delete();

    $count_import_product = import_products_th::where('lot_id', $request->lot_id)->count();
    if ($count_import_product < 1) {
      $lot->delete();
    }

    return redirect('importDetailTh?id=' . $request->lot_id)->with(['error' => 'insert_success']);
  }

  public function changeImportItemWeightTh(Request $request)
  {

    $import_product = import_products_th::where('id', $request->lot_item_id_in_weight)->first();

    $lot = Lots_th::where('id', $request->lot_id_in_weight)
      ->orderBy('id', 'DESC')->first();

    Lots_th::where('id', $request->lot_id_in_weight)->update(
      [
        'total_base_price' => (($lot->total_base_price - ($import_product->base_price * $import_product->weight)) + ($request->base_price_in_weight * $request->weight_in_weight)),
        'total_price' => (($lot->total_price - ($import_product->real_price * $import_product->weight)) + ($request->real_price_in_weight * $request->weight_in_weight)),
        'total_main_price' => (($lot->total_price - ($import_product->real_price * $import_product->weight)) + ($request->real_price_in_weight * $request->weight_in_weight) + ($lot->fee ? $lot->fee : 0) + ($lot->pack_price ? $lot->pack_price : 0)),
      ]
    );

    $import_product = import_products_th::where('id', $request->lot_item_id_in_weight)->update(
      [
        'weight' => $request->weight_in_weight,
      ]
    );

    return redirect('importDetailTh?id=' . $request->lot_id_in_weight)->with(['error' => 'insert_success']);
  }

  public function deleteLotTh(Request $request)
  {
    $lot = lots_th::where('id', $request->id);
    $lot->delete();
    $import_products = import_products_th::where('lot_id', $request->id);
    $import_products->delete();
    return redirect('importViewTh')->with(['error' => 'delete_success']);
  }

  public function paidLotTh(Request $request)
  {
    $lot = Lots_th::where('id', $request->id)->update(
      [
        'payment_status' => 'paid'
      ]
    );
    return redirect('importViewTh')->with(['error' => 'insert_success']);
  }

  public function changeImportWeightTh(Request $request)
  {

    $base_price_kg = $request->lot_base_price_kg ? $request->lot_base_price_kg : 0;
    $real_price_kg = $request->lot_real_price_kg ? $request->lot_real_price_kg : 0;
    $base_price_m = $request->lot_base_price_m ? $request->lot_base_price_m : 0;
    $real_price_m = $request->lot_real_price_m ? $request->lot_real_price_m : 0;
    $weight_m = import_products_th::where('lot_id', $request->lot_id_in_weight)
      ->where('weight_type', 'm')
      ->sum('weight');

    Lots_th::where('id', $request->lot_id_in_weight)->update(
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

    return redirect('importViewTh')->with(['error' => 'insert_success']);
  }
}
