<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TrackingCusca extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tracking_cusca';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'tracking_detail',
        'executed',
        'monthly_actions',
        'budget_executed',
        'user_id',        
        'year_id',        
        //'month_id',        
        'traking_status_id',        
        'actions_cusca_id',        
        'tracking_observation_cusca_id',        
    ];
    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

     public function actionsCusca()
    {
        return $this->belongsTo(ActionsCusca::class, 'actions_cusca_id', 'id');
    }
}
