<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'years';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'year_name',
        'period_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function monthlyClosings()
    {
        return $this->hasMany(MonthlyClosing::class);
    }

    public function poaClosings()
    {
        return $this->hasMany(poaClosings::class);
    }

    
}
