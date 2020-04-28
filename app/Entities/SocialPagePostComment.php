<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class SocialPagePostComment.
 *
 * @package namespace App\Entities;
 */
class SocialPagePostComment extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_page_post_id',
        'provider_id',
        'message',
    ];

    public function post()
    {
        return $this->hasOne(SocialPagePost::class, 'id', 'social_page_post_id');
    }
}
