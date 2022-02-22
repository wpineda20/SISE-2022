<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AxisCuscatlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'axes_cuscatlan';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'axis_description',
        'percentage',
        'create_date',
        'user_id',
        'programmatic_objectives_id_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function objectives()
    {
        return $this->belongsTo(Programmatic_Objective::class, 'programmatic_objectives_id', 'id');
    }
}
