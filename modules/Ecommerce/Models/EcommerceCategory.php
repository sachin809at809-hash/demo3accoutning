<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class EcommerceCategory extends Model
{
    use Tenants;

    protected $table = 'ecommerce_categories';

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(EcommerceProduct::class, 'category_id');
    }
}
