<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order; // Order ilişkisi için bunu ekleyelim
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- EKLENDİ

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // 'name' yerine bunları ekledik:
        'first_name',
        'last_name',

        'email',
        'password',

        // Yeni alanlarımız:
        'phone',
        'company_name',
        'tax_office',
        'tax_id',
        'subscribed_to_newsletter',
        'user_type',
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        // --- BU FONKSİYONU GÜNCELLEDİM ---
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // Yeni boolean alanlarımızı ekledik:
            'is_approved' => 'boolean',
            'subscribed_to_newsletter' => 'boolean',
        ];
        // --- BURAYA KADAR ---
    }

    /**
     * Get the user's initials
     * DİKKAT: Bu fonksiyon 'name' sütununu kullanıyordu,
     * 'first_name' ve 'last_name' olarak güncelledim.
     */
    public function initials(): string
    {
        $firstNameInitial = Str::substr($this->first_name ?? '', 0, 1);
        $lastNameInitial = Str::substr($this->last_name ?? '', 0, 1);
        return strtoupper($firstNameInitial . $lastNameInitial);
    }
    protected function name(): Attribute
    {
        return Attribute::make(
            // Getter (name çağrıldığında ne dönecek)
            get: fn () => $this->first_name . ' ' . $this->last_name,
            // Setter (name'e değer atanmaya çalışıldığında ne yapacak)
            set: function ($value) {
                $parts = explode(' ', $value, 2); // İsmi ilk boşluktan ikiye ayır
                $this->attributes['first_name'] = $parts[0]; // İlk parça first_name
                $this->attributes['last_name'] = $parts[1] ?? ''; // İkinci parça last_name (yoksa boş string)
            }
        );
    }
    /**
     * Bu kullanıcının (müşterinin) verdiği tüm siparişleri getirir.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }
}