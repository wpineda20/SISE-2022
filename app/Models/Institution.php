<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'institutions';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'institution_name',
        'description',
        'mission',
        'vision',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function directions()
    {
        return $this->hasMany(Institution::class);
    }

    public function users()
    {
        return $this->hasMany(Institution::class);
    }

    public function indicators()
    {
        return $this->hasMany(Institution::class);
    }

    public function objectives()
    {
        return $this->hasMany(Institution::class);
    }
}
