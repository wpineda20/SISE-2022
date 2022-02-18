<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'indicators';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'indicator_name',
        'strategic_indicator',
        'unit_id',
        'institution_id',
        'organizational_unit_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'organizational_unit_id', 'id');
    }
}
