<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\CapabilityEnum;
use App\Enums\RoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'role_id',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        "full_name"
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

    public function role() : BelongsTo{
        return $this->belongsTo(Role::class);
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function cart() : HasOne{
        return $this->hasOne(Cart::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    //On override la méthode create pour ajouter le role_id s'il n'est pas présent.
    public static function create(array $attributes = []){
        if(!isset($attributes['role_id'])){
            $attributes['role_id'] = Role::where('slug', RoleEnum::User->value)->first()->id;
        }
        return parent::create($attributes);
    }

    // On crée un panier pour l'utilisateur lors de sa création
    protected static function booted(){
        static::created(function($user){
            $user->cart()->create();
        });
    }


    public function canAccessPanel(Panel $panel): bool
    {
        $capability = CapabilityEnum::UseBackoffice;
        return $this->role->capabilities->where('slug', $capability->value)->isNotEmpty();
    }

    public function getFilamentName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullNameAttribute(): string{
        return $this->first_name . ' ' . $this->last_name;
    }

}
