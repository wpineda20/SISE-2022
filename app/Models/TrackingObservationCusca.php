<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TrackingObservationCusca extends Model
{
   use HasFactory, SoftDeletes;

    protected $table = 'tracking_observation_cusca';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'observation',
        'observation_reply',
        'reply_date',
        'year_id',        
        'month_id',        
        'actions_cusca_id',        
    ];
    
    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;
}
