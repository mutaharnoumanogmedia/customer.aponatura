<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInternal extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku',
        'internal_code',
        'batch_number',
        'location',
        'stock_quantity',
        'min_stock_threshold',
        'max_stock_threshold',
        'unit',
        'price',
        'wholesale_price',
        'retail_price',
        'product_type',
        'file_path',
        'download_url',
        'license_key',
        'max_downloads',
        'access_expires_at',
        'is_active',
        'is_discontinued',
        'status',
        'received_at',
        'expires_at',
        'notes',
        'attributes',
        'image_path',
        'created_by',
        'updated_by',
    ];

    //scope is_active
    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}
