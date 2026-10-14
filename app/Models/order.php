<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\factories\HasFactory;

class order extends Model
{
    use HasFactory;
    
    protected $table = 'orders';
    protected $guarded = ['id'];

    public function orderdetails()
    {
        return $this->hasMany(orderdetail::class, 'order_id');
    }
}
