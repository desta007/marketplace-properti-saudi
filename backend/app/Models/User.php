<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp_number',
        'password',
        'role',
        'language',
        'avatar',
        'rega_license_number',
        'rega_license_document',
        'agent_status',
        'agent_rejection_reason',
        'agent_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'agent_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User's properties (for agents)
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * User's favorite properties
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'favorites')->withTimestamps();
    }

    /**
     * Leads received by agent
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'agent_id');
    }

    /**
     * Check if user is an agent
     */
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if agent is verified
     */
    public function isVerifiedAgent(): bool
    {
        return $this->isAgent() && $this->agent_status === 'verified';
    }

    /**
     * Check if agent status is pending
     */
    public function isPendingAgent(): bool
    {
        return $this->isAgent() && $this->agent_status === 'pending';
    }

    /**
     * Get WhatsApp click-to-chat URL
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        $number = $this->whatsapp_number ?: $this->phone;
        if (!$number) {
            return null;
        }
        // Remove non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);
        return "https://wa.me/{$number}";
    }

    /**
     * Validate REGA license format
     * Format: 10-digit number (simplified validation)
     */
    public static function validateRegaLicenseFormat(?string $license): bool
    {
        if (!$license) {
            return false;
        }
        // REGA license should be alphanumeric, 10-20 characters
        return preg_match('/^[A-Z0-9]{10,20}$/i', $license) === 1;
    }

    /**
     * Scope for verified agents
     */
    public function scopeVerifiedAgents($query)
    {
        return $query->where('role', 'agent')->where('agent_status', 'verified');
    }

    /**
     * Scope for pending agents
     */
    public function scopePendingAgents($query)
    {
        return $query->where('role', 'agent')->where('agent_status', 'pending');
    }
}
