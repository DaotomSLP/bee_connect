<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Import_products;

class ImportProductApi extends Controller
{
  public function import_products($id)
  {
    $import_product = Import_products::query();

    $import_product->select('import_products.code', 'import_products.status', 'import_products.created_at as receive_at', 'import_products.weight', 'import_products.weight_type', 'branchs.branch_name', 'branchs.phone as branch_contact')
      ->join('lot', 'lot.id', 'import_products.lot_id')
      ->join('branchs', 'branchs.id', 'lot.receiver_branch_id')
      ->where('import_products.code', $id);

    if ($import_product->first()) {
      return response()
        ->json($import_product->first());
    } else {
      $import_product->code = $id;
      $import_product->status = "waiting";
      $import_product->weight = "";
      $import_product->weight_type = "";
      $import_product->receive_at = "";
      $import_product->branch_name = "";
      $import_product->branch_contact = "";
      return response()->json($import_product);
    }
  }
}
