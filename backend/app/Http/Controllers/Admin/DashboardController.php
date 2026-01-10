<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_properties' => Property::count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'active_properties' => Property::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'pending_agents' => User::pendingAgents()->count(),
            'verified_agents' => User::verifiedAgents()->count(),
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
        ];

        // Recent pending properties
        $pendingProperties = Property::with(['user', 'city'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent pending agents
        $pendingAgents = User::pendingAgents()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingProperties', 'pendingAgents'));
    }
}
