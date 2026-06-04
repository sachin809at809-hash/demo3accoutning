<?php
namespace Modules\Ecommerce\Models;
use Illuminate\Database\Eloquent\Model;
class StoreUser extends Model {
    protected $table = 'ecommerce_store_users';
    protected $fillable = ['name', 'email', 'password'];
}