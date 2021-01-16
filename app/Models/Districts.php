<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    use HasFactory;

            /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'districts';

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'dist_name',
        'prov_id',
    ];

            /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
