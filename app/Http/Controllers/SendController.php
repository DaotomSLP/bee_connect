<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinces;
use App\Models\Districts;
use App\Models\Branchs;
use App\Models\Import_products;
use App\Models\Product;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class SendController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $provinces = Provinces::all();
        $districts = Districts::all();
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->get();

        $result = Product::query();

        $result->select(
            'products.*',
            'send.branch_name as sender_branch_name',
            'receive.branch_name as receiver_branch_name'
        )
            ->join('branchs As send', 'products.sender_branch_id', 'send.id')
            ->join('branchs As receive', 'products.receiver_branch_id', 'receive.id')
            ->where('products.type', 'domestic');

        if (Auth::user()->is_admin != '1') {
            $result->where('products.sender_branch_id', Auth::user()->branch_id);
        }

        if ($request->send_date != '') {
            $result->whereDate('products.created_at', '=', DateTime::createFromFormat('Y-m-d', $request->send_date));
        }
        if ($request->id != '') {
            $result->where('products.id', $request->id);
        }
        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->receive_branch != '') {
            $result->where('receiver_branch_id', $request->receive_branch);
        }

        $all_products = $result->orderBy('products.id', 'desc')
            ->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $products = $result->orderBy('products.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' =>  ceil(sizeof($all_products) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_products)
        ];

        return view('send', compact('provinces', 'districts', 'branchs', 'products', 'pagination'));
    }
}
