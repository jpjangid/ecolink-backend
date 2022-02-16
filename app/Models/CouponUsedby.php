<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsedby extends Model
{
    use HasFactory;

    protected $table = 'coupon_usedby';

    protected $fillable = [
        'coupon_id', 'user_id', 'applied_times', 'amount', 'order_id'
    ];

    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon', 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}
