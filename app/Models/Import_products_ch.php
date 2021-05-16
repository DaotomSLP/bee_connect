<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import_products_ch extends Model
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'import_products_ch';

    protected $fillable = [
        'id',
        'code',
        'created_at',
        'updated_at',
        'received_at',
        'success_at',
        'receiver_branch_id',
        'weight',
        'weight_type',
        'base_price',
        'total_base_price',
        'real_price',
        'total_real_price',
        'sale_price',
        'total_sale_price',
        'status',
        'delivery_type',
        'addr_detail',
        'shipping_fee',
        'lot_id',
        'receive_branch_id',
        'sale_id'
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
