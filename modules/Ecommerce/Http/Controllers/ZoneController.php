<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommerceDeliveryZone;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = EcommerceDeliveryZone::all();
        return view('ecommerce::zones', compact('zones'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'polygon_data' => 'required|string',
            'delivery_fee' => 'required|numeric',
            'estimated_minutes' => 'nullable|integer',
        ]);
        
        EcommerceDeliveryZone::create([
            'company_id' => company_id(),
            'name' => $request->name,
            'polygon_data' => $request->polygon_data,
            'delivery_fee' => $request->delivery_fee,
            'estimated_minutes' => $request->estimated_minutes,
            'is_active' => true,
        ]);
        
        flash('Delivery zone created successfully!')->success();
        
        return redirect()->route('ecommerce.zones');
    }

    public function destroy($id)
    {
        $zone = EcommerceDeliveryZone::findOrFail($id);
        $zone->delete();

        flash('Delivery zone deleted successfully!')->success();

        return redirect()->route('ecommerce.zones');
    }
}
