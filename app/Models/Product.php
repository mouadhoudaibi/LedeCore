<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'sku',
        'price',
        'promo_price',
        'stock_quantity',
        'slug',
        'image',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'promo_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the effective price (promo_price if set, otherwise price).
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->promo_price ?? $this->price;
    }

    /**
     * Check if product has promotional pricing.
     */
    public function hasPromoPrice(): bool
    {
        return $this->promo_price !== null && $this->promo_price < $this->price;
    }

    /**
     * Calculate discount percentage.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->hasPromoPrice()) {
            return null;
        }

        return (int) round((($this->price - $this->promo_price) / $this->price) * 100);
    }
}
