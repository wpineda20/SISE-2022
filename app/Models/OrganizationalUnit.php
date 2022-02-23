<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrganizationalUnit extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'organizational_units';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'ou_name',
        'direction_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(OrganizationalUnit::class);
    }

    public function indicators()
    {
        return $this->hasMany(OrganizationalUnit::class);
    }
    public function strategys()
    {
        return $this->hasMany(OrganizationalUnit::class);
    }
}
