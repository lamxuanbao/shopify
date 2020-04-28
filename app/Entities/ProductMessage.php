<?php

namespace App\Entities;

/**
 * Class ProductMessage.
 *
 * @package namespace App\Entities;
 */
class ProductMessage extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'message',
    ];

//    public function product()
//    {
//        return $this->hasOne(Product::class, 'id', 'product_id');
//    }
}
