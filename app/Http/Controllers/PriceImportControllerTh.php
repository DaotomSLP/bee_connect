<?php

namespace App\Http\Controllers;

use App\Models\Change_sale_price_history;
use App\Models\Import_products_th;
use Illuminate\Http\Request;
use App\Models\Price_imports_th;
use App\Models\Sale_import_th;
use App\Models\Sale_prices;
use App\Models\Sale_prices_th;
use DateTime;
use Illuminate\Support\Facades\Auth;

class PriceImportControllerTh extends Controller
{
    /** 
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function priceImportTh(Request $request)
    {
        $pagination = [
            'offsets' =>  ceil(sizeof(Price_imports_th::all()) / 10),
            'offset' => 1,
            'all' => sizeof(Price_imports_th::all())
        ];

        $result = Price_imports_th::query();

        $result = Price_imports_th::orderBy('id', 'desc');

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

        return view('priceImportTh', compact('prices', 'pagination'));
    }

    public function insertPriceImportTh(Request $request)
    {
        $price = new Price_imports_th;
        $price->base_price = $request->base_price;
        $price->real_price = $request->real_price;
        $price->weight_type = $request->weight_type;
        if ($price->save()) {
            return redirect('priceImportTh')->with(['error' => 'insert_success']);
        } else {
            return redirect('priceImportTh')->with(['error' => 'not_insert']);
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

    public function editSalePriceTh(Request $request)
    {
        import_products_th::where('id', $request->sale_item_id)->update(
            [
                'sale_price' => $request->new_price,
                'total_sale_price' => $request->new_price
            ]
        );

        $sale_import = Sale_import_th::where('id', $request->sale_id)
            ->orderBy('id', 'DESC')->first();

        Sale_import_th::where('id', $request->sale_id)->update(
            [
                'total' => ($sale_import->total - ($request->old_price)) + ($request->new_price),
                'subtotal' => ($sale_import->subtotal - ($request->old_price)) + ($request->new_price)
            ]
        );

        $change_sale_price_history = new Change_sale_price_history;
        $change_sale_price_history->new_price = $request->new_price;
        $change_sale_price_history->sale_item_id = $request->sale_item_id;
        $change_sale_price_history->branch_id = Auth::user()->branch_id;
        if ($change_sale_price_history->save()) {
            return redirect('saleDetailTh?id=' . $request->sale_id)->with(['error' => 'insert_success']);
        } else {
            return redirect('saleDetailTh?id=' . $request->sale_id)->with(['error' => 'not_insert']);
        }
    }

    public function saleImportPriceTh(Request $request)
    {
        $pagination = [
            'offsets' =>  ceil(sizeof(Price_imports_th::all()) / 10),
            'offset' => 1,
            'all' => sizeof(Price_imports_th::all())
        ];

        $result = Sale_prices_th::query();

        $result = Sale_prices_th::orderBy('id', 'desc');

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

        return view('saleImportPriceTh', compact('prices', 'pagination'));
    }


    public function insertSalePriceImportTh(Request $request)
    {
        $price = new Sale_prices_th;
        $price->price = $request->price;
        $price->weight_type = $request->weight_type;
        $price->branch_id = Auth::user()->branch_id;
        if ($price->save()) {
            return redirect('saleImportPriceTh')->with(['error' => 'insert_success']);
        } else {
            return redirect('saleImportPriceTh')->with(['error' => 'not_insert']);
        }
    }
}
