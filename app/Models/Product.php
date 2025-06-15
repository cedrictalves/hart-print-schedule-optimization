<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    // The attributes that are fillable for mass assignment
    protected $fillable = ['name', 'type'];

    // A product can belong to many order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
