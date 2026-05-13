<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'fish_category_id',
        'quantity',
        'cost_price',
        'total_cost',
        'created_by',
        'purchase_date',
        'supplier_name',
        'invoice_number',
        'notes'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'purchase_date' => 'date'
    ];

    public function fishCategory()
    {
        return $this->belongsTo(FishCategory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
