<?php

namespace App\Http\Controllers;

use App\Models\Delivery_rounds;
use App\Models\Districts;
use App\Models\Provinces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryRoundController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        if (Auth::user()->is_admin != 1) {
            return redirect('access_denied');
        }

        $result = Delivery_rounds::select('delivery_rounds.*');

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $all_delivery_rounds = $result->count();

        $delivery_rounds = $result
            ->orderBy('delivery_rounds.id', 'desc')
            ->limit(10)
            ->get();

        $pagination = [
            'offsets' => ceil($all_delivery_rounds / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => $all_delivery_rounds,
        ];

        return view('delivery_rounds', compact('delivery_rounds', 'pagination'));
    }

    public function insert(Request $request)
    {
        $delivery_round = new Delivery_rounds;
        $delivery_round->round = $request->round;
        $delivery_round->month = $request->month;
        $delivery_round->departure_time = $request->departure_time;

        if ($delivery_round->save()) {
            return redirect('delivery_rounds')->with(['error' => 'insert_success']);
        } else {
            return redirect('delivery_rounds')->with(['error' => 'not_insert']);
        }
    }

    public function edit($id)
    {
        $delivery_round = Delivery_rounds::select('delivery_rounds.*')
            ->where('delivery_rounds.id', $id)
            ->first();

        return view('editDeliveryRound', compact('delivery_round'));
    }

    public function update(Request $request)
    {
        $delivery_round = [
            'round' => $request->round,
            'month' => $request->month,
            'departure_time' => $request->departure_time,
        ];

        if (Delivery_rounds::where('id', $request->id)->update($delivery_round)) {
            return redirect('delivery_rounds')->with(['error' => 'insert_success']);
        } else {
            return redirect('delivery_rounds')->with(['error' => 'not_insert']);
        }
    }

    public function delete($id)
    {
        $delivery_round = [
            'enabled' => '0'
        ];

        if (Delivery_rounds::where('id', $id)->update($delivery_round)) {
            return redirect('delivery_rounds')->with(['error' => 'delete_success']);
        } else {
            return redirect('delivery_rounds')->with(['error' => 'not_insert']);
        }
    }
}
