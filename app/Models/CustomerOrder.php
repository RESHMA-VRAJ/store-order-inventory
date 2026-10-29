<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerOrder extends Model
{
      use HasFactory;
     protected $fillable = [
        'customer_id',
        'subtotal',
        'tax',
        'grand_total',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customer_order_details()
    {
        return $this->hasMany(CustomerOrderDetail::class);
    }
}
