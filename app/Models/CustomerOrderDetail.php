<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerOrderDetail extends Model
{
    use HasFactory;
     protected $fillable = [
        'customer_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'tax_percentage',
        'line_total',
    ];

    public function customer_order()
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
