<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'fish_category_id',
        'quantity',
        'cost_price',
        'stock_date',
        'status',
        'added_by',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'stock_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function fishCategory()
    {
        return $this->belongsTo(FishCategory::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getRemainingQuantityAttribute()
    {
        if ($this->status !== 'approved') {
            return 0;
        }

        $totalSold = Sale::where('fish_category_id', $this->fish_category_id)
                          ->sum('quantity');

        $totalStock = Stock::where('fish_category_id', $this->fish_category_id)
                           ->where('status', 'approved')
                           ->sum('quantity');

        return max(0, $totalStock - $totalSold);
    }

    public function getAvailableStockAttribute()
    {
        return Stock::where('fish_category_id', $this->fish_category_id)
                   ->where('status', 'approved')
                   ->sum('quantity') - 
                   Sale::where('fish_category_id', $this->fish_category_id)
                       ->sum('quantity');
    }
}
