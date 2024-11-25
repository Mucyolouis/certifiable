<?php

namespace App\Models;

use App\Models\Parish;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Church extends Model
{
    use HasFactory, SoftDeletes;

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
        $usersInstance = $this->users()->byRoles(['christian', 'pastor']);
        $baptizedChristiansInstance = clone $usersInstance;
        $unbaptizedInstance = clone $usersInstance;
        $pastorsInstance = clone $usersInstance;

        $total = $usersInstance->count();
        $baptizedChristians = $baptizedChristiansInstance
            ->baptizedChristians()
            ->count();
        $unbaptized = $unbaptizedInstance->unbaptized()
            ->count();
        $pastors = $pastorsInstance->role('pastor')
            ->count();

        return sprintf(
            "Total: %d\nBaptized Christians: %d\nUnbaptized: %d\nPastors: %d",
            $total,
            $baptizedChristians,
            $unbaptized,
            $pastors
        );
    }
}