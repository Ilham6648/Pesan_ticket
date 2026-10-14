<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class orderdetail extends Model
{
    use HasFactory;

    protected $table = 'orderdetails';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(order::class, 'order_id');
    }

    public function event()
    {
        return $this->belongsTo(event::class, 'event_id');
    }
}
