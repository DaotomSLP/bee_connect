<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Price;

class PriceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = [
            'offsets' =>  ceil(sizeof(Price::all()) / 10),
            'offset' => 1,
            'all' => sizeof(Price::all())
        ];

        $result = Price::query();

        $result = Price::orderBy('id', 'desc');

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

        return view('price', compact('prices', 'pagination'));
    }

    public function insert(Request $request)
    {
        $price = new Price;
        $price->price = $request->price;
        $price->weight_type = $request->weight_type;
        if ($price->save()) {
            return redirect('price')->with(['error' => 'insert_success']);
        } else {
            return redirect('price')->with(['error' => 'not_insert']);
        }
    }
}
