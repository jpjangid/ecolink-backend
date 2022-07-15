<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = ['country_code', 'state_code', 'zip', 'city', 'rate', 'tax_name', 'priority', 'compound', 'shipping', 'tax_class', 'state_name'];
}
