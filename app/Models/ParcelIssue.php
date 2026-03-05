<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelIssue extends Model
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parcel_issues';

    protected $fillable = [
        'id',
        'parcel_code',
        'parcel_price',
        'detail',
        'status',
        'receiver_branch_id',
        'user_id',
        'received_at',
        'expired_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
