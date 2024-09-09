<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ministry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = 
        [
            'name',
            'description',
        ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'christian_ministries');
    }
}