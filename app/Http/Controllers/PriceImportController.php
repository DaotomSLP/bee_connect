<?php

namespace App\Http\Controllers;

use App\Models\Change_sale_price_history;
use App\Models\Import_products;
use App\Models\import_products_th;
use App\Models\Price_imports;
use Illuminate\Http\Request;
use App\Models\Price_imports_th;
use App\Models\Sale_import;
use App\Models\Sale_import_th;
use App\Models\Sale_prices;
use App\Models\Sale_prices_th;
use DateTime;
use Illuminate\Support\Facades\Auth;

class PriceImportController extends Controller
{
    /** 
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function priceImport(Request $request)
    {
        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $pagination = [
            'offsets' =>  ceil(sizeof(Price_imports::all()) / 10),
            'offset' => 1,
            'all' => sizeof(Price_imports::all())
        ];

        $result = Price_imports::query();

        $result = Price_imports::orderBy('id', 'desc');

        if ($request->unit != '') {
            $result->where('weight_type', $request->unit);
        }

        if ($request->date != '') {
            $result->whereDate('created_at', '=',  $request->date);
        }

        $all_price = $result->orderBy('id', 'desc')
            ->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $prices = $result->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $pagination = [
            'offsets' =>  ceil(sizeof($all_price) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_price)
        ];

        return view('priceImport', compact('prices', 'pagination'));
    }

    public function insertPriceImport(Request $request)
    {
        $price = new Price_imports;
        $price->base_price = $request->base_price;
        $price->real_price = $request->real_price;
        $price->weight_type = $request->weight_type;
        if ($price->save()) {
            return redirect('priceImport')->with(['error' => 'insert_success']);
        } else {
            return redirect('priceImport')->with(['error' => 'not_insert']);
        }
    }

    public function insertSalePriceImport(Request $request)
    {
        $price = new Sale_prices;
        $price->price = $request->price;
        $price->weight_type = $request->weight_type;
        $price->branch_id = Auth::user()->branch_id;
        if ($price->save()) {
            return redirect('saleImportPrice')->with(['error' => 'insert_success']);
        } else {
            return redirect('saleImportPrice')->with(['error' => 'not_insert']);
        }
    }

    public function editSalePrice(Request $request)
    {

        $sale_import = Sale_import::where('id', $request->sale_id)
            ->orderBy('id', 'DESC')->first();
        Import_products::where('id', $request->sale_item_id)->update(
            [
                'sale_price' => $request->new_price,
                'total_sale_price' => $request->new_price * $request->new_weight,
                'weight_branch' => $request->new_weight
            ]
        );


        Sale_import::where('id', $request->sale_id)->update(
            [
                'total' => ($sale_import->total - ($request->old_price * $request->old_weight)) + ($request->new_price * $request->new_weight) - $sale_import->discount,
                'subtotal' => ($sale_import->total - ($request->old_price * $request->old_weight)) + ($request->new_price * $request->new_weight)
            ]
        );

        $change_sale_price_history = new Change_sale_price_history;
        $change_sale_price_history->new_price = $request->new_price;
        $change_sale_price_history->sale_item_id = $request->sale_item_id;
        $change_sale_price_history->branch_id = Auth::user()->branch_id;
        if ($change_sale_price_history->save()) {
            return redirect('saleView')->with(['error' => 'insert_success']);
        } else {
            return redirect('saleDetail?id=' . $request->sale_id)->with(['error' => 'not_insert']);
        }
    }

    public function saleImportPrice(Request $request)
    {

        if (Auth::user()->is_branch != 1) {
            return redirect('access_denied');
        }

        $pagination = [
            'offsets' =>  ceil(sizeof(Price_imports::all()) / 10),
            'offset' => 1,
            'all' => sizeof(Price_imports::all())
        ];

        $result = Sale_prices::query();

        $result = Sale_prices::orderBy('id', 'desc');

        if ($request->unit != '') {
            $result->where('weight_type', $request->unit);
        }

        if ($request->date != '') {
            $result->whereDate('created_at', '=',  $request->date);
        }

        $all_price = $result->orderBy('id', 'desc')
            ->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $prices = $result->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $pagination = [
            'offsets' =>  ceil(sizeof($all_price) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_price)
        ];

        return view('saleImportPrice', compact('prices', 'pagination'));
    }
}
