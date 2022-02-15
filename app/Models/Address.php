<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'address_name',
        'institution_id',
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
}
