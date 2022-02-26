<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_id', 'order_item_id', 'product_id', 'quantity', 'amount', 'reason', 'description', 'status', 'return_no'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\OrderItems', 'order_item_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
