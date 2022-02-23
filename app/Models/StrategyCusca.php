<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StrategyCusca extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'strategy_cuscas';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'description_strategy',
        'create_date',
        'user_create_strategy',
        'percentage',
        'organizational_units_id',
        'programmatic_objectives_id',        
    ];
    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function organizationalUnits()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'organizational_units_id', 'id');
    }

    public function programaticObjectives()
    {
        return $this->belongsTo(Programmatic_Objective::class, 'programmatic_objectives_id', 'id');
    }
}
