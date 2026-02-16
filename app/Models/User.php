<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'id_card_code',
        'password',
        'division_id',
        'google_id',
        'avatar',
        'is_admin',
        'last_login_at',
        'preferences',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'last_login_at' => 'datetime',
            'preferences' => 'array',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the division that the user belongs to
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get all user sessions (enrollments)
     */
    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get enrolled wellness sessions
     */
    public function wellnessSessions()
    {
        return $this->belongsToMany(WellnessSession::class, 'user_sessions')
                    ->withPivot(['status', 'enrolled_at', 'notes', 'rating', 'feedback'])
                    ->withTimestamps();
    }

    /**
     * Get enrolled schedule items
     */
    public function scheduleItems()
    {
        return $this->belongsToMany(ScheduleItem::class, 'user_sessions')
                    ->withPivot(['status', 'enrolled_at', 'notes', 'rating', 'feedback'])
                    ->withTimestamps();
    }

    /**
     * Get user's selected sessions for My PL
     */
    public function selectedSessions()
    {
        return $this->hasMany(UserSelectedSession::class);
    }

    /**
     * Get user's selected sessions for current academic year
     */
    public function currentYearSelections()
    {
        return $this->selectedSessions()->currentYear();
    }

    /**
     * Check if user has selected a specific session
     */
    public function hasSelected($selectable): bool
    {
        return $this->selectedSessions()
            ->where('selectable_type', get_class($selectable))
            ->where('selectable_id', $selectable->id)
            ->exists();
    }

    /**
     * Add a session to My PL
     */
    public function addToMyPL($selectable, ?string $academicYear = null): UserSelectedSession
    {
        return $this->selectedSessions()->firstOrCreate([
            'selectable_type' => get_class($selectable),
            'selectable_id' => $selectable->id,
        ], [
            'academic_year' => $academicYear ?? PDDay::getCurrentAcademicYear(),
        ]);
    }

    /**
     * Remove a session from My PL
     */
    public function removeFromMyPL($selectable): bool
    {
        return $this->selectedSessions()
            ->where('selectable_type', get_class($selectable))
            ->where('selectable_id', $selectable->id)
            ->delete() > 0;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if user is Non-Teaching Staff (NTS)
     */
    public function isNTS(): bool
    {
        return $this->division?->name === 'NTS';
    }

    /**
     * Get user's division name
     */
    public function getDivisionNameAttribute(): ?string
    {
        return $this->division?->name;
    }

    /**
     * Automatically detect division from email
     */
    public static function detectDivisionFromEmail(string $email): ?int
    {
        // AES email pattern detection
        if (str_contains($email, 'aes.ac.in')) {
            // Example patterns - adjust based on actual email structure
            if (str_contains($email, 'es.') || str_contains($email, 'elementary')) {
                $division = Division::where('name', 'ES')->first();
            } elseif (str_contains($email, 'ms.') || str_contains($email, 'middle')) {
                $division = Division::where('name', 'MS')->first();
            } elseif (str_contains($email, 'hs.') || str_contains($email, 'high')) {
                $division = Division::where('name', 'HS')->first();
            } else {
                // Default to staff or determine based on other criteria
                $division = Division::where('name', 'HS')->first(); // Default
            }
            
            return $division?->id;
        }
        
        return null;
    }
}
