<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductMessage.
 *
 * @package namespace App\Entities;
 */
class ProductMessage extends Model
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
