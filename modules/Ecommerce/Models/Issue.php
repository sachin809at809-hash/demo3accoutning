<?php
namespace Modules\Ecommerce\Models;
use Illuminate\Database\Eloquent\Model;
class Issue extends Model {
    protected $table = 'ecommerce_issues';
    protected $fillable = ['customer_name', 'subject', 'description', 'status'];
}