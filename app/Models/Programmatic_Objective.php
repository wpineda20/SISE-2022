<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Programmatic_Objective extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'programmatic_objectives';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'description',
        'strategy_objective',
        'create_date',
        'percentage',
        'institution_id',
        'user_id',        
    ];
    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
