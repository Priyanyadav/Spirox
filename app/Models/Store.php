<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function get_sales()
    {
        return $this->belongsTo(SalesExecutive::class, 'sales_fk', 'id');
    }
}
