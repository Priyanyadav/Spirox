<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesExecutiveSalesHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function get_sales_order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_fk', 'id');
    }

    public function get_sales_exe()
    {
        return $this->belongsTo(SalesExecutive::class, 'sales_exe_fk', 'id');
    }
}
