<?php
namespace Modules\Ecommerce\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model {
    protected $table = 'ecommerce_reviews';
    protected $fillable = ['product_id', 'author_name', 'rating', 'comment', 'status'];
}