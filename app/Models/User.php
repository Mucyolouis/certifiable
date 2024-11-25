<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Firefly\FilamentBlog\Traits\HasBlog;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar, HasName, HasMedia
{
    use HasApiTokens, 
        HasFactory, 
        HasPanelShield,
        HasRoles,
        HasUuids,
        InteractsWithMedia,
        Notifiable, 
        Authorizable, 
        SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function marriagesAsSpouse1(): HasMany
    {
        return $this->hasMany(Marriage::class, 'spouse1_id');
    }

    public function marriagesAsSpouse2(): HasMany
    {
        return $this->hasMany(Marriage::class, 'spouse2_id');
    }

    public function officiatedMarriages(): HasMany
    {
        return $this->hasMany(Marriage::class, 'officiated_by');
    }

    // Filament-related methods
    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatars')?->first()?->getUrl() ?? 
               $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? 
               null;
    }

    // Accessors & Mutators
    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getFullnameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    // Media Conversions
    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    // Authorization methods
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('filament-shield.super_admin.name'));
    }

    public function canImpersonate(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function canBeImpersonated(): bool
    {
        return !$this->hasRole('super_admin');
    }

    public function canComment(): bool
    {
        return true;
    }

    // Marriage-related methods
    public function isMarried(): bool
    {
        return $this->marital_status === 'married';
    }

    public function isSingle(): bool
    {
        return $this->marital_status === 'single';
    }

    // Scopes
    public function scopeBaptizedChristians($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', 'christian');
        })->where('baptized', 1);
    }

    public function scopeUnbaptized($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', 'christian');
        })->where('baptized', 0);
    }

    public function scopeByRoles($query, $roles)
    {
        return $query->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        });
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
}