<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class EcommerceProductImage extends Model
{
    use Tenants;

    protected $table = 'ecommerce_product_images';

    protected $fillable = [
        'company_id',
        'product_id',
        'image_path',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(EcommerceProduct::class, 'product_id');
    }
}
