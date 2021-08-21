<?php

namespace App\Http\Controllers;

use App\Models\Import_products;
use App\Models\Lots;
use App\Models\Sale_import;
use App\Models\Sale_prices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class SaleImportProductsController extends Controller
{
  public function saleImport(Request $request)
  {

    if(Auth::user()->is_branch != 1){
      return redirect('access_denied');
    }

    $sale_price_gram = Sale_prices::where('weight_type', 'kg')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();


    $sale_price_m = Sale_prices::where('weight_type', 'm')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();

    return view('saleImport', compact('sale_price_gram', 'sale_price_m'));
  }

  public function insertSaleImport(Request $request)
  {

    if(Auth::user()->is_branch != 1){
      return redirect('access_denied');
    }

    if ($request->item_id) {

      $sum_price = 0;
      $count = 0;
      foreach ($request->sale_price as $price) {
        $sum_price += $price * $request->weight[$count];
        $count++;
      }

      $sale_import = new Sale_import;
      $sale_import->branch_id = Auth::user()->branch_id;
      $sale_import->total = $sum_price - ($request->discount == "" ? 0 : $request->discount);
      $sale_import->discount = $request->discount == "" ? 0 : $request->discount;
      $sale_import->subtotal = $sum_price;


      if ($sale_import->save()) {
        $count = 0;
        foreach ($request->item_id as $product_code) {

          $import_product = Import_products::where('id', $product_code)
            ->orderBy('id', 'DESC')->first();

          $lot = Lots::where('id', $import_product->lot_id)
            ->orderBy('id', 'DESC')->first();

          if ($import_product->weight_type != 'm') {
            $new_import_product_update =  [
              'status' => 'success',
              'success_at' => Carbon::now(),
              'sale_id' => $sale_import->id,
              'weight' => $request->weight[$count],
              'weight_branch' => $request->weight[$count],
              'sale_price' => $request->sale_price[$count],
              'total_base_price' => ($lot->total_base_price_kg / $lot->weight_kg) * $request->weight[$count],
              'total_real_price' => ($lot->total_unit_kg / $lot->weight_kg) * $request->weight[$count],
              'total_sale_price' =>  $request->sale_price[$count] * $request->weight[$count]
            ];
          } else {
            $new_import_product_update =  [
              'status' => 'success',
              'success_at' => Carbon::now(),
              'sale_id' => $sale_import->id,
              'sale_price' => $request->sale_price[$count],
              'weight_branch' => $request->weight[$count],
              'total_sale_price' =>  $request->sale_price[$count] * $request->weight[$count]
            ];
          }

          if (Import_products::where('id', $product_code)->update($new_import_product_update)) {

            $import_product = Import_products::where('id', $product_code)
              ->orderBy('id', 'DESC')->first();

            $count_status = Import_products::where('status', 'success')->where('lot_id', $import_product->lot_id)->get();
            $all = Import_products::where('lot_id', $import_product->lot_id)->get();

            $sum_sale_price = Import_products::where('lot_id', $import_product->lot_id)->where('status', 'success')->sum('total_sale_price');
            Lots::where('id', $import_product->lot_id)->update(['total_sale_price' => $sum_sale_price]);

            if ($count_status == $all) {
              Lots::where('id', $import_product->lot_id)->update(['status' => 'success']);
            }
          } else {
            Import_products::where('sale_id', $sale_import->id)
              ->where('weight_type', 'kg')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'weight' => '',
                'total_sale_price' =>  ''
              ]);

            Import_products::where('sale_id', $sale_import->id)
              ->where('weight_type', 'm')
              ->update([
                'status' => 'received',
                'success_at' => '',
                'sale_id' => '',
                'sale_price' => '',
                'total_sale_price' =>  ''
              ]);
            $sale = Sale_import::where('id', $sale_import->id);
            $sale->delete();
            return redirect('saleImport')->with(['error' => 'not_insert']);
          }

          $count++;
        }
        return redirect('saleImport')->with(['error' => 'insert_success', 'id' => $sale_import->id]);
      } else {
        return redirect('saleImport')->with(['error' => 'not_insert']);
      }
    } else {
      return redirect('saleImport')->with(['error' => 'not_insert']);
    }
  }

  public function saleView(Request $request)
  {

    if(Auth::user()->is_branch != 1){
      return redirect('access_denied');
    }

    $result = Sale_import::query();

    $result->select('sale_import.*')->where('branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import.created_at', '=', $request->send_date);
    }
    if ($request->id != '') {
      $result->where('sale_import.id', $request->id);
    }

    $all_sale_imports = $result->orderBy('sale_import.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $sale_imports = $result->orderBy('sale_import.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_sale_imports / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_sale_imports
    ];

    return view('saleView', compact('sale_imports', 'pagination'));
  }

  public function salereport($id)
  {

    if(Auth::user()->is_branch != 1){
      return redirect('access_denied');
    }

    $sale = Sale_import::find($id);
    $items = Import_products::where('sale_id', $id)->get();

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

    if(Auth::user()->is_branch != 1){
      return redirect('access_denied');
    }

    $result = Import_products::query();

    $result->select('import_products.*')
      ->join('sale_import', 'sale_import.id', 'import_products.sale_id')
      ->where('sale_import.id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import.created_at', '=',  $request->send_date);
    }

    if ($request->product_id != '') {
      $result->where('import_products.code', $request->product_id);
    }

    $all_import_products = $result->orderBy('sale_import.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_import_products / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_import_products
    ];

    return view('saleDetail', compact('import_products', 'pagination'));
  }
}
