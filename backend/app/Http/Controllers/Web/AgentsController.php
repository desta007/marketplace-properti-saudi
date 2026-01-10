<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AgentsController extends Controller
{
    /**
     * List all verified agents
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'agent')
            ->where('agent_status', 'verified')
            ->withCount('properties');

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rega_license_number', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'properties');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('agent_verified_at', 'desc');
                break;
            case 'properties':
            default:
                $query->orderBy('properties_count', 'desc');
                break;
        }

        $agents = $query->paginate(12);

        return view('web.agents.index', compact('agents'));
    }

    /**
     * Show agent profile with their properties
     */
    public function show(User $agent)
    {
        // Only show verified agents
        if (!$agent->isVerifiedAgent()) {
            abort(404);
        }

        $properties = $agent->properties()
            ->where('status', 'active')
            ->with(['city', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('web.agents.show', compact('agent', 'properties'));
    }
}
