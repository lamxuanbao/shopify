<?php

namespace App\Entities;

/**
 * Class Social.
 *
 * @package namespace App\Entities;
 */
class Social extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'token',
        'refresh_token',
        'expires_in',
    ];

    public function scopeProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function pages()
    {
        return $this->hasMany(SocialPage::class, 'social_id');
    }
}
