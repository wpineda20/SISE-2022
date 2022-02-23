<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ResultsCusca extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'results_cusca';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'result_description',
        'responsible_name',
        'percentage',
        'create_date',
        'user_id',
        'axis_cusca_id',
        'indicator_id',
        'organizational_units_id',
    ];
    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;
}
