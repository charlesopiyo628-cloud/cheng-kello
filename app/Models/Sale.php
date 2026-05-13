<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'fish_category_id',
        'quantity',
        'unit_price',
        'total_amount',
        'sold_by',
        'sale_date',
        'customer_name',
        'customer_phone',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'sale_date' => 'date'
    ];

    public function fishCategory()
    {
        return $this->belongsTo(FishCategory::class);
    }

    public function soldBy()
    {
        return $this->belongsTo(User::class, 'sold_by');
    }
}
