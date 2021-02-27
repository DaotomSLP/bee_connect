<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'received_at',
        'success_at',
        'sender_branch_id',
        'receiver_branch_id',
        'weight',
        'weight_type',
        'price',
        'cust_send_name',
        'cust_send_tel',
        'cust_receiver_name',
        'cust_receiver_tel',
        'status',
        'type',
        'payment_type',
        'payment_status',
        'second_branch_payment_status'
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
