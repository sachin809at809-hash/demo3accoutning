<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class EcommerceDeliveryZone extends Model
{
    use Tenants;

    protected $table = 'ecommerce_delivery_zones';

    protected $fillable = [
        'company_id',
        'name',
        'polygon_data',
        'delivery_fee',
        'estimated_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delivery_fee' => 'float',
    ];
}
