<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Expenditure;
use App\Models\Import_products;
use App\Models\IncomeCh;
use App\Models\Lots;
use App\Models\Price_imports;
use App\Models\Provinces;
use App\Models\Service_charge;
use App\Models\User;
use App\Models\WithdrawCh;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

use function PHPSTORM_META\type;

class ImportProductsController extends Controller
{
  public function index(Request $request)
  {

    $provinces = Provinces::all();
    $districts = Districts::all();
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    // $lots = Lots::all();

    // foreach ($lots as $key => $value) {
    //   $new_lot = ['total_main_price' => $value->total_price];
    //   Lots::where('id', $value->id)->update($new_lot);
    // }

    if (Auth::user()->is_admin == 1) {
      return view('import', compact('provinces', 'districts', 'branchs'));
    } else {
      return view('importForUser', compact('provinces', 'districts', 'branchs'));
    }
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
      $to_date_now = date('Y-m-d',  strtotime($request->to_date));
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

    if (Auth::user()->branch_id == null) {
      $sum_weight_kg_branch = 0;
      $sum_weight_m_branch = 0;
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

      $sum_weight_kg_branch = Lots::whereBetween('lot.created_at', [$date, $to_date])
        ->where('lot.receiver_branch_id', Auth::user()->branch_id)
        ->sum("weight_kg");

      $sum_weight_m_branch = Lots::whereBetween('lot.created_at', [$date, $to_date])
        ->where('lot.receiver_branch_id', Auth::user()->branch_id)
        ->sum("weight_m");

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

    $import_product_count_for_chart = DB::table('lot')
      ->select(DB::raw('count(import_products.id) as count_import_product'))
      ->join('import_products', 'lot.id', 'import_products.lot_id')
      ->join('branchs', 'lot.receiver_branch_id', 'branchs.id')
      ->whereBetween('lot.created_at', [$date, $to_date])
      ->groupBy('lot.receiver_branch_id')->get()
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

    return view('dailyimport', compact('sum_base_price', 'sum_real_price', 'sum_sale_profit', 'sum_profit', 'sum_expenditure', 'date_now', 'branch_sale_totals', 'pagination', 'to_date_now', 'import_product_count', 'result_paid', 'result_unpaid', 'sum_fee_price', 'sum_pack_price', 'sum_share', 'result_weight', 'result_weight_m', 'sum_weight_kg_branch', 'sum_weight_m_branch', 'branch_sale_totals_branch_name', 'branch_sale_totals_kg', 'branch_sale_totals_m', 'import_product_count_for_chart', 'branch_sale_totals_price', 'branch_sale_totals_unpaid', 'branch_sale_totals_paid'));
  }

  public function insertImport(Request $request)
  {

    if (Auth::user()->is_admin != 1) {
      return redirect('access_denied');
    }

    if ($request->item_id) {

      $sum_price = 0;
      $sum_m_weight = 0;
      $count = 0;

      foreach ($request->weight_type as $weight_type) {
        if ($weight_type == 'm') {
          $sum_m_weight += $request->weight[$count];
        } else {
          if ($request->weight_kg <= 0) {
            return redirect('import')->with(['error' => 'not_insert']);
          }
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
      $lot->total_main_price = $sum_price + $request->fee + $request->pack_price + $sum_service_charge;
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
      $lot->service_charge = $sum_service_charge;
      $lot->weight_m = $sum_m_weight;

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

        if (isset($request->service_item_price)) {
          foreach ($request->service_item_price as $key => $price) {
            $service_charge = new Service_charge;
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
          ->orderBy('id', 'DESC')->first();

        $new_import_product_update =  [
          'received_at' => Carbon::now(),
          'status' => 'received',
        ];

        if (Import_products::where('id', $import_product->id)->update($new_import_product_update)) {
          $count_status = Import_products::where('status', 'sending')->where('lot_id', $import_product->lot_id)->count();
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


    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();
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
      $result->whereDate('lot.created_at', '=',  $request->send_date);
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

    $all_lots = $result->orderBy('lot.id', 'desc')
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_lots / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_lots
    ];

    return view('importView', compact('branchs', 'lots', 'pagination'));
  }

  public function importViewForUser(Request $request)
  {

    if (Auth::user()->is_branch != 1) {
      return redirect('access_denied');
    }

    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();
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
      $result->whereDate('lot.created_at', '=',  $request->send_date);
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
      ->count();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $lots = $result->orderBy('lot.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil($all_lots / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => $all_lots
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
      'weight_kg' => $lot->weight_kg,
      'price' => $lot->total_main_price,
      'pack_price' => $lot->pack_price,
      'fee' => $lot->fee,
      'service_charge' => $lot->service_charge,
    ];
    $pdf = PDF::loadView('pdf.import', $data);
    return $pdf->stream('document.pdf');
  }

  public function importDetail(Request $request)
  {

    if (Auth::user()->is_admin != 1) {
      return redirect('access_denied');
    }

    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $lot = Lots::join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')->where('lot.id', $request->id)->get();

    $result = Import_products::query();

    $result->select('import_products.*')
      ->where('lot_id', $request->id);

    if ($request->send_date != '') {
      $result->whereDate('import_products.created_at', '=',  $request->send_date);
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

    // echo ($import_products));
    // exit;

    return view('importDetail', compact('branchs', 'import_products', 'pagination', 'lot'));
  }

  public function importDetailForUser(Request $request)
  {

    if (Auth::user()->is_branch != 1) {
      return redirect('access_denied');
    }

    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products::query();

    $result->select('import_products.*')
      ->join('lot', 'lot.id', 'import_products.lot_id')
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id')
      ->where('lot_id', $request->id)
      ->where('lot.receiver_branch_id', Auth::user()->branch_id);

    if ($request->send_date != '') {
      $result->whereDate('import_products.created_at', '=',  $request->send_date);
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

    return view('importDetailForUser', compact('branchs', 'import_products', 'pagination'));
  }


  public function importProductTrack(Request $request)
  {

    if (Auth::user()->is_admin != 1) {
      return redirect('access_denied');
    }

    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products::query();

    $result->select('import_products.*', 'receive.branch_name')
      ->join('lot', 'lot.id', 'import_products.lot_id')
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id');

    if ($request->send_date != '') {
      $result->whereDate('import_products.created_at', '=',  $request->send_date);
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
        Service_charge::where('id', $request->service_item_id[$key])->update(
          [
            'name' => $request->service_item_name[$key],
            'price' => $price
          ]
        );
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

    $result->select('import_products.*', 'receive.branch_name')
      ->join('lot', 'lot.id', 'import_products.lot_id')
      ->join('branchs As receive', 'lot.receiver_branch_id', 'receive.id');

    if ($request->send_date != '') {
      $result->whereDate('import_products.received_at', '=',  $request->send_date);
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

    return view('allImportDetailForUser', compact('import_products', 'pagination', 'branchs'));
  }

  public function getImportProduct(Request $request)
  {

    $import_product = Import_products::select('import_products.*')->join('lot', 'lot.id', 'import_products.lot_id')->where('code', $request->id)->where('lot.receiver_branch_id', Auth::user()->branch_id)->orderBy('import_products.id', 'desc')->first();

    if ($import_product) {
      return response()
        ->json($import_product);
    } else {
      return response()
        ->json(['error' => '1']);
    }
  }

  public function receiveImport(Request $request)
  {

    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products::query();

    $result->select('import_products.*', 'branchs.branch_name')
      ->join('branchs', 'import_products.receiver_branch_id', 'branchs.id')
      ->where('import_products.receiver_branch_id', Auth::user()->branch_id);

    if ($request->receive_date != '') {
      $result->whereDate('import_products.created_at', '=',  $request->receive_date);
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

    $all_products = $result->orderBy('import_products.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_products) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_products)
    ];

    return view('receiveimport', compact('products', 'pagination', 'branchs'));
  }

  public function deleteImportItem(Request $request)
  {

    if (Auth::user()->is_owner != 1) {
      return redirect('access_denied');
    }

    $lot = Lots::where('id', $request->lot_id)
      ->orderBy('id', 'DESC')->first();

    Lots::where('id', $request->lot_id)->update(
      [
        'total_base_price' => ($lot->total_base_price - ($request->base_price * $request->weight)),
        'total_price' => ($lot->total_price - ($request->real_price * $request->weight)),
        'weight_kg' => $lot->weight_kg - ($request->weight_type == 'm' ? 0 : $request->weight),
      ]
    );

    $import_product = Import_products::where('id', $request->lot_item_id);
    $import_product->delete();

    $count_import_product = Import_products::where('lot_id', $request->lot_id)->count();
    if ($count_import_product < 1) {
      $lot->delete();
    }

    return redirect()->back()->with(['error' => 'insert_success']);
  }

  public function changeImportItemWeight(Request $request)
  {

    if (Auth::user()->is_owner != 1) {
      return redirect('access_denied');
    }

    $import_product = Import_products::where('id', $request->lot_item_id_in_weight)->first();

    $lot = Lots::where('id', $request->lot_id_in_weight)
      ->orderBy('id', 'DESC')->first();

    Lots::where('id', $request->lot_id_in_weight)->update(
      [
        'total_base_price' => (($lot->total_base_price - ($import_product->base_price * $import_product->weight)) + ($request->base_price_in_weight * $request->weight_in_weight)),
        'total_price' => (($lot->total_price - ($import_product->real_price * $import_product->weight)) + ($request->real_price_in_weight * $request->weight_in_weight)),
        'total_main_price' => (($lot->total_price - ($import_product->real_price * $import_product->weight)) + ($request->real_price_in_weight * $request->weight_in_weight) + ($lot->fee ? $lot->fee : 0) + ($lot->pack_price ? $lot->pack_price : 0)),
      ]
    );

    $import_product = Import_products::where('id', $request->lot_item_id_in_weight)->update(
      [
        'weight' => $request->weight_in_weight,
      ]
    );

    return redirect()->back()->with(['error' => 'insert_success']);
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
    return redirect()->back()->with(['error' => 'delete_success']);
  }

  public function paidLot(Request $request)
  {

    if (Auth::user()->is_admin != 1) {
      return redirect('access_denied');
    }

    lots::where('id', $request->id)->update(
      [
        'payment_status' => 'paid'
      ]
    );

    $sum_price = Lots::where('id', $request->id)->sum('total_main_price') - Lots::where('id', $request->id)->sum('total_base_price');

    $income_ch = new IncomeCh();
    $income_ch->price = $sum_price;
    $income_ch->lot_id = $request->id;
    $income_ch->save();
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

    Lots::where('id', $request->lot_id_in_weight)->update(
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
    $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

    $result = Import_products::query();

    $result->select('import_products.*', 'branchs.branch_name')
      ->join('branchs', 'import_products.receiver_branch_id', 'branchs.id')
      ->where('import_products.receiver_branch_id', Auth::user()->branch_id)
      ->where('type', 'import');

    if ($request->receive_date != '') {
      $result->whereDate('import_products.created_at', '=',  $request->receive_date);
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

    $all_products = $result->orderBy('import_products.id', 'desc')
      ->get();

    if ($request->page != '') {
      $result->offset(($request->page - 1) * 25);
    }

    $products = $result->orderBy('import_products.id', 'desc')
      ->limit(25)
      ->get();

    $pagination = [
      'offsets' =>  ceil(sizeof($all_products) / 25),
      'offset' => $request->page ? $request->page : 1,
      'all' => sizeof($all_products)
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
}