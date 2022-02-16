<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MonthlyClosing extends Model
{
   use HasFactory, SoftDeletes;

    protected $table = 'monthly_closings';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'year_id',
        'month_id',
        'end_month',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;
}
