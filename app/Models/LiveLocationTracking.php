<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveLocationTracking extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function get_sales_exe()
    {
        return $this->belongsTo(SalesExecutive::class, 'sales_exec_id', 'id');
    }
}
