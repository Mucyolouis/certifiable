<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Marriage extends Model
{
    use HasFactory,SoftDeletes,HasUuids;
    protected $fillable = [
        'spouse1_id',
        'spouse2_id',
        'officiated_by',
        'marriage_date',
        'certificate_number',
    ];

    protected $casts = [
        'marriage_date' => 'date',
    ];

    public function spouse1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'spouse1_id');
    }

    public function spouse2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'spouse2_id');
    }

    public function officiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officiated_by');
    }

    public static function getModelLabel(): string
    {
        return 'Marriage';
    }

    protected function spouse1FullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->spouse1->firstname . ' ' . $this->spouse1->lastname,
        );
    }

    protected function spouse2FullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->spouse2->firstname . ' ' . $this->spouse2->lastname,
        );
    }

    protected function officiatedByFullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->officiated_by) {
                    $user = User::find($this->officiated_by);
                    return $user ? $user->firstname . ' ' . $user->lastname: 'N/A';
                }
                return 'N/A';
            },
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($marriage) {
            $marriage->spouse1->update(['marital_status' => 'single']);
            $marriage->spouse2->update(['marital_status' => 'single']);
        });
    }
}
