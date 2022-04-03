<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'type', 'min_order_amount', 'max_order_amount', 'offer_start', 'status', 'offer_end', 'coupon_limit', 'times_applied', 'disc_type', 'discount', 'show_in_front', 'flag', 'cat_id', 'product_id', 'user_id', 'days'
    ];

    public function coupon_used()
    {
        return $this->hasMany('App\Models\CouponUsedBy', 'coupon_id', 'id');
    }
}
