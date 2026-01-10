<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * List all agents
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'agent');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('agent_status', $request->status);
        }

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('rega_license_number', 'like', "%{$search}%");
            });
        }

        $agents = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Show agent detail for verification
     */
    public function show(User $agent)
    {
        $agent->load('properties');
        return view('admin.agents.show', compact('agent'));
    }

    /**
     * Verify agent
     */
    public function verify(Request $request, User $agent)
    {
        $agent->update([
            'agent_status' => 'verified',
            'agent_verified_at' => Carbon::now(),
            'agent_rejection_reason' => null,
        ]);

        // TODO: Send notification to agent

        if ($request->ajax()) {
            return response()->json(['message' => 'Agent verified successfully']);
        }

        return redirect()->back()->with('success', 'Agent verified successfully');
    }

    /**
     * Reject agent verification
     */
    public function reject(Request $request, User $agent)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $agent->update([
            'agent_status' => 'rejected',
            'agent_rejection_reason' => $request->reason,
        ]);

        // TODO: Send notification to agent with rejection reason

        if ($request->ajax()) {
            return response()->json(['message' => 'Agent rejected']);
        }

        return redirect()->back()->with('success', 'Agent application rejected');
    }

    /**
     * Suspend agent
     */
    public function suspend(Request $request, User $agent)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $agent->update([
            'agent_status' => 'rejected',
            'agent_rejection_reason' => $request->reason,
        ]);

        // Deactivate all their properties
        $agent->properties()->update(['status' => 'rejected']);

        if ($request->ajax()) {
            return response()->json(['message' => 'Agent suspended']);
        }

        return redirect()->back()->with('success', 'Agent suspended');
    }
}
