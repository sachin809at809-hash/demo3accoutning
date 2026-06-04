<?php
namespace Modules\Ecommerce\Models;
use Illuminate\Database\Eloquent\Model;
class Brand extends Model {
    protected $table = 'ecommerce_brands';
    protected $fillable = ['name', 'slug', 'description'];
}