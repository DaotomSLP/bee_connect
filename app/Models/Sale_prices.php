<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_prices extends Model
{
    use HasFactory;

    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sale_price';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'price',
        'weight_type'
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
