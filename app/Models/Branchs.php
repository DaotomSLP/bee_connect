<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchs extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branchs';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'district_id',
        'branch_name',
        'is_owner',
        'first_name',
        'last_name',
        'phone'
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
