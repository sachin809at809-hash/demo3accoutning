<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceInventoryBatch extends Model
{
    protected $table = 'ecommerce_inventory_batches';

    protected $fillable = [
        'company_id',
        'variant_id',
        'batch_number',
        'quantity',
        'expiration_date',
    ];

    public function variant()
    {
        return $this->belongsTo(EcommerceProductVariant::class, 'variant_id');
    }
}
