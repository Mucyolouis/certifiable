<?php

namespace App\Models;

use App\Models\Parish;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Church extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['parish_id', 'name'];

    public function parish()
    {
        return $this->belongsTo(Parish::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getUsersSummaryAttribute()
    {
        $total = $this->users()->count();
        $baptizedChristians = $this->users()->role('christian')->where('baptized', 1)->count();
        $unbaptized = $this->users()->where('baptized', 0)->count();
        $pastors = $this->users()->role('pastor')->count();
        
        return "Total: {$total}\nBaptized Christians: {$baptizedChristians}\nUnbaptized: {$unbaptized}\nPastors: {$pastors}";
    }
}
