<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lots_th extends Model
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lot_th';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'received_at',
        'receiver_branch_id',
        'total_base_price_kg',
        'total_base_price_m',
        'total_unit_m',
        'total_unit_kg',
        'weight_kg',
        'total_base_price',
        'total_main_price',
        'total_price',
        'total_sale_price',
        'status',
        'payment_status',
        'fee',
        'pack_price',
        'lot_base_price_kg',
        'lot_real_price_kg',
        'lot_base_price_m',
        'lot_real_price_m'
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
