<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActionsCusca extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'actions_cusca';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'action_description',
        'annual_actions',
        'executed',
        'responsable_name',
        'verification_method',
        'data_source',
        'mesure_unit',
        'budget_executed',
        'user_id',
        'results_cusca_id',
        'month_id',
        'year_id'
    ];
    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $timestamps = false;


}
