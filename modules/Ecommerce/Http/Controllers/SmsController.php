<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    /**
     * Display the SMS dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $smsLimit = 2000;
        $smsSent = 218;
        $smsLeft = $smsLimit - $smsSent;
        $percentage = ($smsSent / $smsLimit) * 100;

        $events = [
            ['name' => 'Order Received', 'template' => 'Hi {customer_name}, we have received your order {order_id}.', 'is_active' => true],
            ['name' => 'Order Processing', 'template' => 'Your order {order_id} is now being processed.', 'is_active' => true],
            ['name' => 'Order Dispatched', 'template' => 'Great news! Your order {order_id} has been dispatched.', 'is_active' => false],
            ['name' => 'Order Delivered', 'template' => 'Your order {order_id} has been delivered. Enjoy!', 'is_active' => true],
            ['name' => 'Refund Initiated', 'template' => 'We have initiated a refund for order {order_id}.', 'is_active' => false],
        ];

        $apiKey = setting('ecommerce.sms_api_key', '');
        $isConnected = !empty($apiKey);

        return view('ecommerce::sms.index', compact('smsLimit', 'smsSent', 'smsLeft', 'percentage', 'events', 'apiKey', 'isConnected'));
    }

    /**
     * Connect the SMS API by saving the key.
     */
    public function connect(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string|max:255'
        ]);

        setting()->set('ecommerce.sms_api_key', $request->api_key);
        setting()->save();

        flash('Successfully connected to the SMS Provider!')->success();

        return redirect()->route('ecommerce.sms.index');
    }
}
