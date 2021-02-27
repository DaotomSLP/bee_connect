<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Import_products;
use App\Models\Product;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiveController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

        $result = Product::query();

        $result->select('products.*', 'branchs.branch_name')
            ->join('branchs', 'products.receiver_branch_id', 'branchs.id')
            ->where('products.receiver_branch_id', Auth::user()->branch_id);

        if ($request->receive_date != '') {
            $result->whereDate('products.created_at', '=',  $request->receive_date);
        }
        if ($request->id != '') {
            $result->where('products.id', $request->id);
        }

        if ($request->status != '') {
            $result->where('status', $request->status);
        }

        if ($request->send_branch != '') {
            $result->where('sender_branch_id', $request->send_branch);
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

        return view('receive', compact('products', 'pagination', 'branchs'));
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
}
