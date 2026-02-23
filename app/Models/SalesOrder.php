<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function get_sales_excutive()
    {
        return $this->belongsTo(SalesExecutive::class, 'sales_executive_fk', 'id');
    }

    public function get_store()
    {
        return $this->belongsTo(Store::class, 'store_fk', 'id');
    }
}
