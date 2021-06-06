<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Import_products;
use App\Models\Import_products_ch;
use App\Models\Lots_ch;
use App\Models\Price_imports;
use App\Models\Provinces;
use App\Models\Sale_import_ch;
use App\Models\Sale_prices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class SaleImportProductsControllerCh extends Controller
{

  public function saleImport(Request $request)
  {
    $sale_price_gram = Sale_prices::where('weight_type', 'kg')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();


    $sale_price_m = Sale_prices::where('weight_type', 'm')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();

    return view('ch_test.saleImport', compact('sale_price_gram', 'sale_price_m'));
  }

  public function insertSaleImport(Request $request)
  {
    if ($request->items) {
      $sum_price = 0;

      foreach ($request->items as $key => $value) {
        $sum_price += ($value["price"] * $value["weight"]);
      }

      $sale_import = new Sale_import_ch;
      $sale_import->branch_id = Auth::user()->branch_id;
      $sale_import->total = $sum_price - ($request->discount == "" ? 0 : $request->discount);
      $sale_import->discount = $request->discount == "" ? 0 : $request->discount;
      $sale_import->subtotal = $sum_price;
      $sale_import->sale_type = "normal";

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

  public function saleView(Request $request)
  {
    $result = Sale_import_ch::query();

    $result->select('sale_import_ch.*')->where('branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import_ch.created_at', '=', $request->send_date);
    }
    if ($request->id != '') {
      $result->where('sale_import_ch.id', $request->id);
    }

    $all_sale_imports = $result->orderBy('sale_import_ch.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $sale_imports = $result->orderBy('sale_import_ch.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_sale_imports / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_sale_imports
    ];

    return view('ch_test.saleView', compact('sale_imports', 'pagination'));
  }

  public function salereport($id)
  {
    $sale = Sale_import_ch::find($id);
    $items = Import_products_ch::where('sale_id', $id)->get();

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

  public function saleDetail(Request $request)
  {

    $result = Import_products_ch::query();

    $result->select('import_products_ch.*')
      ->join('sale_import', 'sale_import_ch.id', 'import_products_ch.sale_id')
      ->where('sale_import_ch.id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import_ch.created_at', '=',  $request->send_date);
    }

    if ($request->product_id != '') {
      $result->where('import_products_ch.code', $request->product_id);
    }

    $all_import_products = $result->orderBy('sale_import_ch.id', 'desc')
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

    return view('ch_test.saleDetail', compact('import_products', 'pagination'));
  }
}
