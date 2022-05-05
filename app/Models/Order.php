<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no', 'user_id', 'order_amount', 'discount_applied', 'service_charge_applied', 'total_amount', 'no_items', 'billing_name', 'billing_mobile', 'billing_address', 'billing_country', 'billing_state', 'billing_city', 'billing_zip', 'billing_landmark', 'shipping_name', 'shipping_mobile', 'shipping_address', 'shipping_country', 'shipping_state', 'shipping_city', 'shipping_zip', 'shipping_landmark', 'order_status', 'payment_via', 'payment_currency', 'payment_status', 'coupon_id', 'order_comments', 'payment_amount', 'wallet_amount', 'coupon_discount', 'shipping_email', 'billing_email', 'flag', 'shippment_via', 'shippment_status'
    ];

    public function items()
    {
        return $this->hasMany('App\Models\OrderItems', 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function return()
    {
        return $this->belongsTo('App\OrderItemReturn', 'order_id', 'id');
    }
}
