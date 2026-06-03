<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class EcommerceOrderItem extends Model
{
    use Tenants;

    protected $table = 'ecommerce_order_items';

    protected $fillable = [
        'company_id',
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'total',
    ];

    public function order()
    {
        return $this->belongsTo(EcommerceOrder::class, 'order_id');
    }
    
    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }
}
