<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class EcommerceOrder extends Model
{
    use Tenants;

    protected $table = 'ecommerce_orders';

    protected $fillable = [
        'company_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'delivery_zone_id',
        'subtotal',
        'delivery_fee',
        'total',
        'status',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(EcommerceOrderItem::class, 'order_id');
    }
    
    public function zone()
    {
        return $this->belongsTo(EcommerceDeliveryZone::class, 'delivery_zone_id');
    }
}
