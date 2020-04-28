<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SocialPage.
 *
 * @package namespace App\Entities;
 */
class SocialPage extends BaseModel
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'provider_id',
        'social_id',
        'access_token',
    ];

    public function conversations()
    {
        return $this->hasMany(SocialPageConversation::class, 'social_page_id');
    }
}
