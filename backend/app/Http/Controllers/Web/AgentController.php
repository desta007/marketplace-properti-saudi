<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Show agent registration form
     */
    public function showRegister()
    {
        $user = auth()->user();

        // Already an agent
        if ($user->isAgent()) {
            return redirect()->route('my-properties')
                ->with('info', 'You are already registered as an agent.');
        }

        return view('web.auth.agent-register');
    }

    /**
     * Process agent registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'rega_license_number' => 'required|string|max:50',
            'rega_license_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'terms' => 'required|accepted',
        ]);

        $user = auth()->user();

        // Validate REGA license format
        if (!User::validateRegaLicenseFormat($request->rega_license_number)) {
            return back()->withErrors([
                'rega_license_number' => 'Invalid REGA license format. Must be 10-20 alphanumeric characters.',
            ])->withInput();
        }

        // Store license document
        $documentPath = $request->file('rega_license_document')
            ->store('agent-licenses/' . $user->id, 'public');

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'role' => 'agent',
            'rega_license_number' => $request->rega_license_number,
            'rega_license_document' => $documentPath,
            'agent_status' => 'pending',
        ]);

        return redirect()->route('profile')
            ->with('success', 'Agent registration submitted! Your application will be reviewed within 1-2 business days.');
    }
}
