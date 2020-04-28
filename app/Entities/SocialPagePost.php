<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class SocialPagePost.
 *
 * @package namespace App\Entities;
 */
class SocialPagePost extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_page_id',
        'provider_id',
        'message',
    ];

    public function page()
    {
        return $this->hasOne(SocialPage::class, 'id', 'social_page_id');
    }

}
