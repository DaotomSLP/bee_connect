<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Provinces;
use App\Models\Districts;
use App\Models\Branchs;
use App\Models\Import_products;
use App\Models\Price;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use PDF;

class ProductController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $provinces = Provinces::all();
        // $districts = Districts::all();
        // $branchs = Branchs::all();
        // return view('send', compact('provinces', 'districts', 'branchs'));
    }

    public function insert(Request $request)
    {

        $price = Price::where('weight_type', $request->weight_type)
            ->orderBy('id', 'DESC')->first();

        $product = new Product;
        $product->receiver_branch_id = $request->receiver_branch_id;
        $product->sender_branch_id = Auth::user()->branch_id;
        $product->weight = $request->weight;
        $product->weight_type = $request->weight_type;
        $product->price = $price->price * $request->weight;
        $product->cust_receiver_name = $request->cust_receiver_name;
        $product->cust_send_name = $request->cust_send_name;
        $product->cust_send_tel = $request->cust_send_tel;
        $product->cust_receiver_tel = $request->cust_receiver_tel;
        $product->status = 'sending';
        $product->type = 'domestic';
        $product->payment_type = $request->payment_type;
        $product->payment_status = 'unpaid';
        $product->second_branch_payment_status = 'unpaid';


        if ($product->save()) {
            return redirect('send')->with(['error' => 'insert_success', 'id' => $product->id]);
        } else {
            return redirect('send')->with(['error' => 'not_insert']);
        }
    }

    public function allProducts(Request $request)
    {
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

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
            $result->where('products.sender_branch_id', Auth::user()->branch_id)
                ->orWhere('products.receiver_branch_id', Auth::user()->branch_id);
        }

        if ($request->send_date != '') {
            $result->whereDate('products.created_at', '=',  $request->send_date);
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

        return view('allProduct', compact('branchs', 'products', 'pagination'));
    }

    public function update(Request $request)
    {
        if (Product::where('id', $request->id)->update(['received_at' => Carbon::now(), 'status' => 'received'])) {
            return redirect('receive')->with(['error' => 'insert_success']);
        } else {
            return redirect('receive')->with(['error' => 'not_insert']);
        }
    }

    public function send(Request $request)
    {
        $provinces = Provinces::all();
        $districts = Districts::all();
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

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
            $result->whereDate('products.created_at', '=',  $request->send_date);
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

    public function receive(Request $request)
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

    public function success(Request $request)
    {
        $branchs = Branchs::where('id', '<>', Auth::user()->branch_id)->where('branchs.enabled', '1')->get();

        $result = Product::query();

        $result->select('products.*', 'branchs.branch_name')
            ->join('branchs', 'products.receiver_branch_id', 'branchs.id')
            ->where('products.receiver_branch_id', Auth::user()->branch_id)
            ->where('type', 'domestic');

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

        return view('success', compact('products', 'pagination', 'branchs'));
    }

    public function paidProduct(Request $request)
    {
        if (Product::where('id', $request->id)->update(['payment_status' => 'paid'])) {
            return redirect('send')->with(['error' => 'insert_success']);
        } else {
            return redirect('send')->with(['error' => 'not_insert']);
        }
    }

    public function paidProductForSecondBranch(Request $request)
    {
        if (Product::where('id', $request->id)->update(['second_branch_payment_status' => 'paid'])) {
            return redirect('allProducts')->with(['error' => 'insert_success']);
        } else {
            return redirect('allProducts')->with(['error' => 'not_insert']);
        }
    }

    public function successProduct(Request $request)
    {
        if (sizeof(Product::where('id', $request->id)
            ->where('status', 'received')->get()) == 1) {
            if (Product::where('id', $request->id)->update(['success_at' => Carbon::now(), 'status' => 'success'])) {
                return redirect('success')->with(['error' => 'insert_success']);
            } else {
                return redirect('success')->with(['error' => 'not_insert']);
            }
        } else {
            return redirect('success')->with(['error' => 'not_in_product']);
        }
    }

    public function successImportProduct(Request $request)
    {
        if (Import_products::where('id', $request->id)->update(['success_at' => Carbon::now(), 'status' => 'success'])) {
            return redirect('success')->with(['error' => 'insert_success']);
        } else {
            return redirect('success')->with(['error' => 'not_insert']);
        }
    }

    public function report($id)
    {
        $product = Product::find($id);
        $sender_branch = Branchs::find($product->sender_branch_id);
        $receive_branch = Branchs::find($product->receiver_branch_id);

        $data = [
            'id' => $product->id,
            'date' => date('d-m-Y', strtotime($product->created_at)),
            'from' => $sender_branch->branch_name,
            'to' => $receive_branch->branch_name,
            'weight' => $product->weight,
            'weight_type' => $product->weight_type,
            'price' => $product->price,
            'cust_receiver_name' => $product->cust_receiver_name,
            'cust_send_tel' => $product->cust_send_tel,
            'cust_send_name' => $product->cust_send_name,
            'cust_receiver_tel' => $product->cust_receiver_tel
        ];
        $pdf = PDF::loadView('pdf.test', $data);
        return $pdf->stream('document.pdf');
    }
}
