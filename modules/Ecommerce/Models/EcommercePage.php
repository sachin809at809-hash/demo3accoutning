<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenants;

class EcommercePage extends Model
{
    use Tenants;

    protected $table = 'ecommerce_pages';

    protected $fillable = [
        'company_id',
        'slug',
        'title',
        'html',
        'css',
        'components',
        'styles',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
