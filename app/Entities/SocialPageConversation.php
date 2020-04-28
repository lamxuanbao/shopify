<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SocialPageConversation.
 *
 * @package namespace App\Entities;
 */
class SocialPageConversation extends BaseModel
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_page_id',
        'provider_id',
        'link',
    ];

    public function detail()
    {
        return $this->hasMany(SocialPageConversationDetail::class, 'social_page_conversation_id');
    }

    public function users()
    {
        return $this->hasMany(SocialPageConversationUser::class, 'social_page_conversation_id');
    }

}
