<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable implements FilamentUser, HasAvatar
{ 
    use HasFactory, Notifiable, HasRoles;

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ( $panel->getId() === 'admin' )
        {
            return str_ends_with($this->email, '@veins-skatingclub.com') 
                && $this->is_admin;
                // && $this->hasVerifiedEmail();
        }
        else 
        {
            return !str_ends_with($this->email, '@veins-skatingclub.com') 
                    //    && $this->hasVerifiedEmail()
                       && !$this->is_admin;
        }
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function members() : HasMany
    {
        return $this->hasMany(Member::class,'parent_id');
    }

    // public function invoices() : HasManyThrough
    // {
    //     return $this->hasManyThrough(Invoice::class, Member::class, 'parent_id', 'member_id');
    // }

    public function invoices() : HasMany
    {
        return $this->hasMany(Invoice::class,'parent_id');
    }

}
