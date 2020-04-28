<?php

namespace App\Entities;

/**
 * Class Shop.
 *
 * @package namespace App\Entities;
 */
class Shop extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'access_token',
        'domain',
        'email',
        'name',
        'user_id',
        'country_code',
        'currency',
        'iana_timezone',
        'country',
        'phone',
        'shop_owner',
        'money_format',
        'money_with_currency_format',
        'weight_unit',
        'plan_name',
        'password_enabled',
        'has_storefront',
        'force_ssl',
    ];

    public function user()
    {
        return $this->hasMany(SocialPageConversation::class, 'social_page_id');
    }
}
