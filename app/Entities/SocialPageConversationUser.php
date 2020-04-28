<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SocialPageConversationUser.
 *
 * @package namespace App\Entities;
 */
class SocialPageConversationUser extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_page_conversation_id',
        'provider_id',
        'name',
        'avatar',
    ];

    public function conversation()
    {
        return $this->hasOne(SocialPageConversation::class, 'id', 'social_page_conversation_id');
    }
}
