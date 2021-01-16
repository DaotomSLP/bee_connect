<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Import_products;
use App\Models\Lots;
use App\Models\Price;
use App\Models\Price_imports;
use App\Models\Provinces;
use App\Models\Sale_import;
use App\Models\Sale_prices;
use Carbon\Carbon;
use DateTime;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use PDF;

class ImportProductsController extends Controller
{
  public function index(Request $request)
  {
    $provinces = Provinces::all();
    $districts = Districts::all();
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->get();

    if (Auth::user()->is_admin == 1) {
      return view('import', compact('provinces', 'districts', 'branchs'));
    } else {
      return view('importForUser', compact('provinces', 'districts', 'branchs'));
    }
  }

  public function saleImport(Request $request)
  {
    $sale_price_gram = Sale_prices::where('weight_type', 'gram')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();

    $sale_price_m = Sale_prices::where('weight_type', 'm')
      ->where('branch_id',  Auth::user()->branch_id)
      ->orderBy('id', 'DESC')->first();

    return view('saleImport', compact('sale_price_gram', 'sale_price_m'));
  }


  public function insertImport(Request $request)
  {

    $sum_price = 0;
    $sum_m_weight = 0;
    $count = 0;
    foreach ($request->weight_type as $weight_type) {
      if ($weight_type == 'm') {
        $sum_m_weight += $request->weight[$count];
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

    $lot = new Lots;
    $lot->receiver_branch_id = $request->receiver_branch_id;
    $lot->weight_kg = $request->weight_kg;
    $lot->total_base_price_kg = $sum_kg_base_price;
    $lot->total_base_price_m = $sum_m_base_price;
    $lot->total_base_price = $sum_base_price;
    $lot->total_price = $sum_price;
    $lot->total_unit_m = $sum_m_price;
    $lot->total_unit_kg = $sum_kg_price;
    $lot->status = 'sending';

    if ($lot->save()) {
      $count = 0;
      foreach ($request->item_id as $product_id) {
        $price = Price_imports::where('weight_type', $request->weight_type[$count])
          ->orderBy('id', 'DESC')->first();

        $product = new Import_products;
        $product->code = $product_id;

        if ($request->weight_type[$count] == 'm') {
          $product->weight = $request->weight[$count];
          $product->base_price = $request->base_price_m == '' ? $price->base_price : $request->base_price_m;
          $product->real_price = $request->real_price_m == '' ? $price->real_price : $request->real_price_m;
          $product->total_base_price = ($request->base_price_m == '' ? $price->base_price : $request->base_price_m) * $request->weight[$count];
          $product->total_real_price = ($request->real_price_m == '' ? $price->real_price : $request->real_price_m) * $request->weight[$count];
          $product->total_sale_price = 0;
        } else {
          $product->weight = 0;
          $product->base_price = $request->base_price_kg == '' ? $price->base_price : $request->base_price_kg;
          $product->real_price = $request->real_price_kg == '' ? $price->real_price : $request->real_price_kg;
          $product->total_base_price = 0;
          $product->total_real_price = 0;
          $product->total_sale_price = 0;
        }

        $product->weight_type = $request->weight_type[$count];
        $product->status = 'sending';
        $product->lot_id = $lot->id;

        if ($product->save()) {
        }

        $count++;
      }
      return redirect('import')->with(['error' => 'insert_success', 'id' => $lot->id]);
    } else {
      return redirect('import')->with(['error' => 'not_insert']);
    }
  }

  public function insertImportForUser(Request $request)
  {

    $count = 0;
    foreach ($request->item_id as $product_code) {

      $import_product = Import_products::where('code', $product_code)
        ->orderBy('id', 'DESC')->first();

      $new_import_product_update =  [
        'received_at' => Carbon::now(),
        'status' => 'received',
      ];

      if (Import_products::where('id', $import_product->id)->update($new_import_product_update)) {
        $count_status = Import_products::where('status', 'sending')->where('lot_id', $import_product->lot_id)->get();
        if (sizeof($count_status) == 0) {
          Lots::where('id', $import_product->lot_id)->update(['status' => 'received']);
        } else {
          Lots::where('id', $import_product->lot_id)->update(['status' => 'not_full']);
        }
      }

      $count++;
    }

    return redirect('import')->with(['error' => 'insert_success']);
  }

  public function insertSaleImport(Request $request)
  {

    $sum_price = 0;
    $count = 0;
    foreach ($request->sale_price as $price) {
      $sum_price += $price * $request->weight[$count];
      $count++;
    }

    $sale_import = new Sale_import;
    $sale_import->branch_id = Auth::user()->branch_id;
    $sale_import->total = $sum_price;

    if ($sale_import->save()) {
      $count = 0;
      foreach ($request->item_id as $product_code) {

        $import_product = Import_products::where('code', $product_code)
          ->orderBy('id', 'DESC')->first();

        $lot = Lots::where('id', $import_product->lot_id)
          ->orderBy('id', 'DESC')->first();

        if ($import_product->weight_type != 'm') {
          $new_import_product_update =  [
            'status' => 'success',
            'success_at' => Carbon::now(),
            'sale_id' => $sale_import->id,
            'weight' => $request->weight[$count],
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
            'weight' => $request->weight[$count],
            'total_sale_price' =>  $request->sale_price[$count] * $request->weight[$count]
          ];
        }

        if (Import_products::where('code', $product_code)->update($new_import_product_update)) {

          $import_product = Import_products::where('code', $product_code)
            ->orderBy('id', 'DESC')->first();

          $count_status = Import_products::where('status', 'success')->where('lot_id', $import_product->lot_id)->get();
          $all = Import_products::where('lot_id', $import_product->lot_id)->get();

          $sum_sale_price = Import_products::where('lot_id', $import_product->lot_id)->where('status', 'success')->sum('total_sale_price');
          Lots::where('id', $import_product->lot_id)->update(['total_sale_price' => $sum_sale_price]);

          if ($count_status == $all) {
            Lots::where('id', $import_product->lot_id)->update(['status' => 'success']);
          }
        }

        $count++;
      }
      return redirect('saleImport')->with(['error' => 'insert_success', 'id' => $sale_import->id]);
    } else {
      return redirect('saleImport')->with(['error' => 'not_insert']);
    }
  }

  public function importView(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->get();
    $result = Lots::query();

    $result->select(
      'lot.*',
      'receive.branch_name as receiver_branch_name'
    )
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id');

    // if (Auth::user()->is_admin != '1') {
    //   $result->where('import_products.sender_branch_id', Auth::user()->branch_id);
    // }

    if ($request->send_date != '') {
      $result->whereDate('lot.created_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
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

    $all_lots = $result->orderBy('lot.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_lots) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_lots)
    ];

    return view('importView', compact('branchs', 'lots', 'pagination'));
  }

  public function saleView(Request $request)
  {
    $result = Sale_import::query();

    $result->select('sale_import.*')->where('branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import.created_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
    }
    if ($request->id != '') {
      $result->where('sale_import.id', $request->id);
    }

    $all_sale_imports = $result->orderBy('sale_import.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $sale_imports = $result->orderBy('sale_import.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_sale_imports) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_sale_imports)
    ];

    return view('saleView', compact('sale_imports', 'pagination'));
  }

  public function importViewForUser(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->get();
    $result = Lots::query();

    $result->select(
      'lot.*',
      'receive.branch_name as receiver_branch_name'
    )
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
      ->where('receiver_branch_id', Auth::user()->branch_id);

    // if (Auth::user()->is_admin != '1') {
    //   $result->where('import_products.sender_branch_id', Auth::user()->branch_id);
    // }

    if ($request->send_date != '') {
      $result->whereDate('lot.created_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
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

    $all_lots = $result->orderBy('lot.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_lots) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_lots)
    ];

    return view('importView', compact('branchs', 'lots', 'pagination'));
  }

  public function report($id)
  {
    $lot = Lots::find($id);
    $receive_branch = Branchs::find($lot->receiver_branch_id);

    $data = [
      'id' => $lot->id,
      'date' => date('d-m-Y', strtotime($lot->created_at)),
      'to' => $receive_branch->branch_name,
      'price' => $lot->total_price
    ];
    $pdf = PDF::loadView('pdf.import', $data);
    return $pdf->stream('document.pdf');
  }

  public function salereport($id)
  {
    $sale = Sale_import::find($id);
    $item = Import_products::where('sale_id', $id)->get();

    $data = [
      'id' => $sale->id,
      'date' => date('d-m-Y', strtotime($sale->created_at)),
      'price' => $sale->total
    ];
    $pdf = PDF::loadView('pdf.sale', $data);
    return $pdf->stream('document.pdf');
  }

  public function importDetail(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->get();

    $result = Import_products::query();

    $result->select('import_products.*')
      ->where('lot_id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('import_products.created_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
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

    $all_import_products = $result->orderBy('import_products.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_import_products) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_import_products)
    ];

    // echo (sizeof($import_products));
    // exit;

    return view('importDetail', compact('branchs', 'import_products', 'pagination'));
  }

  public function saleDetail(Request $request)
  {

    $result = Import_products::query();

    $result->select('import_products.*')
      ->join('sale_import', 'sale_import.id', 'import_products.sale_id')
      ->where('sale_import.id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('sale_import.created_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
    }

    if ($request->product_id != '') {
      $result->where('import_products.code', $request->product_id);
    }

    $all_import_products = $result->orderBy('sale_import.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_import_products) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_import_products)
    ];

    return view('saleDetail', compact('import_products', 'pagination'));
  }

  public function importDetailForUser(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->get();

    $result = Import_products::query();

    $result->select('import_products.*')
      ->join('lot', 'lot.id', 'import_products.lot_id')
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
      ->where('lot_id', $request->id)
      ->where('lot.receiver_branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('import_products.created_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
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

    $all_import_products = $result->orderBy('import_products.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_import_products) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_import_products)
    ];

    return view('importDetailForUser', compact('branchs', 'import_products', 'pagination'));
  }


  public function importProductTrack(Request $request)
  {
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->get();

    $result = Import_products::query();

    $result->select('import_products.*')
      ->join('lot', 'lot.id', 'import_products.lot_id')
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id');

    if ($request->send_date != '') {
      $result->whereDate('import_products.received_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
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

    $all_import_products = $result->orderBy('import_products.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_import_products) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_import_products)
    ];

    return view('allImportDetail', compact('branchs', 'import_products', 'pagination'));
  }

  public function importProductTrackForUser(Request $request)
  {

    $result = Import_products::query();

    $result->select('import_products.*')
      ->join('lot', 'lot.id', 'import_products.lot_id')
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
      ->where('lot.receiver_branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('import_products.received_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
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

    $all_import_products = $result->orderBy('import_products.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $import_products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_import_products) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_import_products)
    ];

    return view('allImportDetailForUser', compact('import_products', 'pagination'));
  }

  public function getImportProduct(Request $request)
  {

    $import_product = Import_products::where('code', $request->id)->first();

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

    $lot = Lots::where('id', $request->lot_id)
      ->orderBy('id', 'DESC')->first();

    Lots::where('id', $request->lot_id)->update(
      [
        'total_base_price' => ($lot->total_base_price - ($request->base_price * $request->weight)),
        'total_real_price' => ($lot->total_real_price - ($request->real_price * $request->weight)),
      ]
    );

    $import_product = Import_products::where('id', $request->lot_item_id);
    $import_product->delete();
  }
}
