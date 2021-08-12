<?php

namespace App\Http\Controllers;

use App\Models\Expenditure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ExpenditureController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(Auth::user()->is_admin != 1){
            return redirect('access_denied');
        }

        $result = Expenditure::query();
        $date_now = date('Y-m-d', strtotime(Carbon::now()));

        $result->select('expenditure.*', 'users.name')
            ->join('users', 'expenditure.user_id', 'users.id');

        if ($request->date_search != '') {
        }

        $all_expenditure = $result->orderBy('expenditure.id', 'desc')
            ->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $expenditure = $result->orderBy('expenditure.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' =>  ceil(sizeof($all_expenditure) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_expenditure)
        ];

        return view('expenditure', compact('expenditure', 'pagination','date_now'));
    }

    public function insert(Request $request)
    {
        $expenditure = new Expenditure;
        $expenditure->created_at = $request->date;
        $expenditure->price = $request->price;
        $expenditure->user_id = Auth::user()->id;
        $expenditure->detail = $request->detail;

        if ($expenditure->save()) {
            return redirect('expenditure')->with(['error' => 'insert_success']);
        } else {
            return redirect('expenditure')->with(['error' => 'not_insert']);
        }
    }
}
