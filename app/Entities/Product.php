<?php

namespace App\Entities;

/**
 * Class Product.
 *
 * @package namespace App\Entities;
 */
class Product extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'title',
        'handle',
        'main_image',
        'images',
        'shop_id',
        'platform',
        'price',
        'link',
        'vendor',
        'body_html',
        'tags',
        'product_prices',
        'compare_at_price',
        'weights',
        'inventory_quantity',
        'variants_titles',
    ];

    protected $casts = [
        'images'     => 'array',
        'main_image' => 'array',
    ];

    public function message()
    {
        return $this->hasMany(ProductMessage::class, 'product_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'shop_id');
    }
}
