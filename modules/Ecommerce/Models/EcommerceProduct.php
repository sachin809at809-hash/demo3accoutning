<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class EcommerceProduct extends Model
{
    use Tenants;

    protected $table = 'ecommerce_products';

    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'sale_price',
        'stock_quantity',
        'weight_kg',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(EcommerceCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(EcommerceProductImage::class, 'product_id')->orderBy('sort_order');
    }
}
