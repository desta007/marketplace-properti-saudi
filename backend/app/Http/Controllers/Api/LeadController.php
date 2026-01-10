<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * List leads for authenticated agent
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Lead::with(['property', 'seeker'])
            ->forAgent($user->id)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by property
        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        $leads = $query->paginate(20);

        return LeadResource::collection($leads);
    }

    /**
     * Get lead statistics for agent dashboard
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => Lead::forAgent($user->id)->count(),
            'new' => Lead::forAgent($user->id)->where('status', 'new')->count(),
            'contacted' => Lead::forAgent($user->id)->where('status', 'contacted')->count(),
            'qualified' => Lead::forAgent($user->id)->where('status', 'qualified')->count(),
            'converted' => Lead::forAgent($user->id)->where('status', 'converted')->count(),
            'this_week' => Lead::forAgent($user->id)
                ->where('created_at', '>=', now()->startOfWeek())
                ->count(),
            'this_month' => Lead::forAgent($user->id)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        return response()->json(['stats' => $stats]);
    }

    /**
     * Create a new lead (public - for property inquiries)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'seeker_name' => 'required|string|max:255',
            'seeker_phone' => 'required|string|max:20',
            'seeker_email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
            'source' => 'nullable|in:whatsapp,phone,chat,form,viewing_request',
        ]);

        $property = Property::findOrFail($validated['property_id']);

        $lead = Lead::create([
            'property_id' => $property->id,
            'agent_id' => $property->user_id,
            'seeker_id' => Auth::id(), // null if not logged in
            'seeker_name' => $validated['seeker_name'],
            'seeker_phone' => $validated['seeker_phone'],
            'seeker_email' => $validated['seeker_email'] ?? null,
            'message' => $validated['message'] ?? null,
            'source' => $validated['source'] ?? 'form',
            'status' => 'new',
        ]);

        return response()->json([
            'message' => 'Inquiry sent successfully',
            'lead' => new LeadResource($lead),
        ], 201);
    }

    /**
     * Get single lead
     */
    public function show(Request $request, Lead $lead)
    {
        // Ensure agent owns this lead
        if ($lead->agent_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lead->load(['property', 'seeker']);

        return new LeadResource($lead);
    }

    /**
     * Update lead status
     */
    public function update(Request $request, Lead $lead)
    {
        // Ensure agent owns this lead
        if ($lead->agent_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'nullable|in:new,contacted,qualified,viewing_scheduled,converted,lost',
            'notes' => 'nullable|string|max:1000',
        ]);

        $lead->update($validated);

        return response()->json([
            'message' => 'Lead updated successfully',
            'lead' => new LeadResource($lead),
        ]);
    }

    /**
     * Delete lead
     */
    public function destroy(Request $request, Lead $lead)
    {
        // Ensure agent owns this lead
        if ($lead->agent_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully']);
    }
}
