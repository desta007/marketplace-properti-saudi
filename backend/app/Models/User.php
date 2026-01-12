<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const FREE_LISTING_LIMIT = 2;

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
     * All subscriptions for this user
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Current active subscription
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->latest();
    }

    /**
     * All transactions for this user
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * All listing credits for this user
     */
    public function listingCredits(): HasMany
    {
        return $this->hasMany(ListingCredit::class);
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
     * Check if user has active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription !== null;
    }

    /**
     * Get current subscription plan
     */
    public function getCurrentPlan(): ?SubscriptionPlan
    {
        $subscription = $this->activeSubscription;
        return $subscription ? $subscription->plan : null;
    }

    /**
     * Get listing limit based on subscription
     * Returns null for unlimited
     */
    public function getListingLimit(): ?int
    {
        $plan = $this->getCurrentPlan();

        if ($plan) {
            return $plan->listing_limit; // null = unlimited
        }

        // No subscription = free tier limit
        return self::FREE_LISTING_LIMIT;
    }

    /**
     * Get count of active listings
     */
    public function getActiveListingsCount(): int
    {
        return $this->properties()
            ->whereIn('status', ['pending', 'active'])
            ->count();
    }

    /**
     * Get remaining listing slots
     * Returns PHP_INT_MAX for unlimited
     */
    public function getRemainingListingSlots(): int
    {
        $limit = $this->getListingLimit();

        if ($limit === null) {
            return PHP_INT_MAX; // Unlimited
        }

        $used = $this->getActiveListingsCount();
        $remaining = $limit - $used;

        // Add available listing credits
        $credits = $this->getAvailableListingCredits();

        return max(0, $remaining) + $credits;
    }

    /**
     * Get available listing credits
     */
    public function getAvailableListingCredits(): int
    {
        return $this->listingCredits()
            ->usable()
            ->get()
            ->sum('remaining_credits');
    }

    /**
     * Check if user can create a new listing
     */
    public function canCreateListing(): bool
    {
        return $this->getRemainingListingSlots() > 0;
    }

    /**
     * Use a listing slot (either from subscription quota or credits)
     * Returns true if successful
     */
    public function useListingSlot(): bool
    {
        $limit = $this->getListingLimit();

        // Unlimited subscription
        if ($limit === null) {
            return true;
        }

        // Check if within base quota
        $used = $this->getActiveListingsCount();
        if ($used < $limit) {
            return true; // Within quota, no need to deduct credits
        }

        // Need to use a listing credit
        $credit = $this->listingCredits()
            ->usable()
            ->orderBy('expires_at')
            ->first();

        if ($credit) {
            return $credit->useCredit();
        }

        return false;
    }

    /**
     * Get remaining featured credits from active subscription
     */
    public function getRemainingFeaturedCredits(): int
    {
        $subscription = $this->activeSubscription;
        return $subscription ? $subscription->featured_credits_remaining : 0;
    }

    /**
     * Use a featured credit from subscription
     */
    public function useFeaturedCredit(): bool
    {
        $subscription = $this->activeSubscription;

        if (!$subscription) {
            return false;
        }

        return $subscription->useFeaturedCredit();
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
