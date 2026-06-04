<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceProductVariant extends Model
{
    protected $table = 'ecommerce_product_variants';

    protected $fillable = [
        'company_id',
        'product_id',
        'name',
        'sku',
        'price',
        'stock_quantity',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }

    public function batches()
    {
        return $this->hasMany(EcommerceInventoryBatch::class, 'variant_id');
    }
}
