<?php

namespace App\Models;

use App\Models\Church;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parish extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name'];

    public function churches()
    {
        return $this->hasMany(Church::class);
    }
}
