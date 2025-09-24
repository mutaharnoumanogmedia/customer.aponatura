<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'website_url',
        'logo_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
        'title',
        'slogan',
        'support_email',
        'is_active',
        'config',
        'slug',
    ];
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function products()
    {
        return $this->hasMany(ShopifyProduct::class, 'brand', 'name');
    }
}
