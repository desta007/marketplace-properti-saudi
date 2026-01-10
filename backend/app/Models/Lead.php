<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    protected $fillable = [
        'property_id',
        'seeker_id',
        'agent_id',
        'seeker_name',
        'seeker_phone',
        'seeker_email',
        'message',
        'source',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status labels for display
     */
    public static function statusLabels(): array
    {
        return [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'viewing_scheduled' => 'Viewing Scheduled',
            'converted' => 'Converted',
            'lost' => 'Lost',
        ];
    }

    /**
     * Source labels for display
     */
    public static function sourceLabels(): array
    {
        return [
            'whatsapp' => 'WhatsApp',
            'phone' => 'Phone Call',
            'chat' => 'In-App Chat',
            'form' => 'Contact Form',
            'viewing_request' => 'Viewing Request',
        ];
    }

    /**
     * Property this lead is for
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Seeker (user who made the inquiry)
     */
    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    /**
     * Agent who owns this lead
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Scope for new leads
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for agent's leads
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Check if lead is new
     */
    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    /**
     * Mark lead as contacted
     */
    public function markAsContacted(): void
    {
        $this->update(['status' => 'contacted']);
    }
}
