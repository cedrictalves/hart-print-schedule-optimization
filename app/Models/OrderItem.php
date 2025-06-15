<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    // The attributes that are fillable for mass assignment
    protected $fillable = ['order_id', 'product_id', 'quantity'];

    // An item belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // An item is for one product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
