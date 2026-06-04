<?php

namespace Modules\OmniChat\Http\Controllers;

use Modules\OmniChat\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\OmniChat\Models\OmnichatCannedResponse;

class CannedResponseController extends Controller
{
    public function index()
    {
        $responses = OmnichatCannedResponse::where('company_id', company_id())->get();
        return view('omni-chat::canned-responses.index', compact('responses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'shortcut' => 'nullable|string',
            'body' => 'required|string',
        ]);

        OmnichatCannedResponse::create([
            'company_id' => company_id(),
            'title' => $request->title,
            'shortcut' => $request->shortcut,
            'body' => $request->body,
        ]);

        flash('Canned response added!')->success();
        return redirect()->back();
    }

    public function destroy($id)
    {
        OmnichatCannedResponse::where('company_id', company_id())->findOrFail($id)->delete();
        flash('Canned response deleted!')->success();
        return redirect()->back();
    }
}
