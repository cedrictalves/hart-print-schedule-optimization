<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    // The attributes that are fillable for mass assignment
    protected $fillable = ['customer_name', 'need_by_date', 'type'];

    // An order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
