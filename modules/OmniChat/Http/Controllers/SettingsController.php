<?php

namespace Modules\OmniChat\Http\Controllers;

use Modules\OmniChat\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\OmniChat\Models\OmnichatChannel;

class SettingsController extends Controller
{
    public function index()
    {
        $channels = OmnichatChannel::all();
        return view('omni-chat::settings', compact('channels'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'platform' => 'required|string',
            'name' => 'required|string',
            'identifier' => 'nullable|string',
        ]);

        OmnichatChannel::updateOrCreate(
            [
                'company_id' => company_id() ?? 1,
                'platform' => $request->platform,
            ],
            [
                'name' => $request->name,
                'identifier' => $request->identifier ?? '',
                'credentials' => [
                    'api_key' => $request->api_key,
                    'secret' => $request->secret,
                    'additional_field' => $request->additional_field, // for extra fields if needed
                ],
                'is_active' => $request->has('is_active') ? true : false,
            ]
        );

        flash(ucfirst($request->platform) . ' settings saved successfully!')->success();

        return redirect()->back();
    }
}
