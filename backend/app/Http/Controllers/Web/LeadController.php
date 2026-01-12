<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of leads for the authenticated agent.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Lead::with(['property', 'property.images'])
            ->where('agent_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Get statistics
        $stats = [
            'total' => Lead::where('agent_id', $user->id)->count(),
            'new' => Lead::where('agent_id', $user->id)->where('status', 'new')->count(),
            'contacted' => Lead::where('agent_id', $user->id)->where('status', 'contacted')->count(),
            'qualified' => Lead::where('agent_id', $user->id)->where('status', 'qualified')->count(),
            'converted' => Lead::where('agent_id', $user->id)->where('status', 'converted')->count(),
        ];

        $leads = $query->paginate(15);

        return view('web.leads.index', compact('leads', 'stats'));
    }

    /**
     * Display a single lead.
     */
    public function show(Lead $lead)
    {
        // Ensure agent owns this lead
        if ($lead->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $lead->load(['property', 'property.images', 'property.city']);

        return view('web.leads.show', compact('lead'));
    }

    /**
     * Update lead status.
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        // Ensure agent owns this lead
        if ($lead->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,viewing_scheduled,converted,lost',
            'notes' => 'nullable|string|max:1000',
        ]);

        $lead->update($validated);

        return redirect()->back()->with('success', 'Lead status updated successfully');
    }
}
