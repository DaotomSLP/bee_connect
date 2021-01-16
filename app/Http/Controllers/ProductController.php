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

        if ($product->save()) {
            return redirect('send')->with(['error' => 'insert_success', 'id' => $product->id]);
        } else {
            return redirect('send')->with(['error' => 'not_insert']);
        }
    }

    public function update(Request $request)
    {
        if (Product::where('id', $request->id)->update(['received_at' => Carbon::now(), 'status' => 'received'])) {
            return redirect('receive')->with(['error' => 'insert_success']);
        } else {
            return redirect('receive')->with(['error' => 'not_insert']);
        }
    }

    public function updateImport(Request $request)
    {
        if (Import_products::where('id', $request->id)->update(['received_at' => Carbon::now(), 'status' => 'received'])) {
            return redirect('receive')->with(['error' => 'insert_success']);
        } else {
            return redirect('receive')->with(['error' => 'not_insert']);
        }
    }

    public function success(Request $request)
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
