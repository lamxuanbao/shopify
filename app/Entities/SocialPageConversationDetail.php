<?php

namespace App\Entities;

use App\Entities\Traits\SyncsWithFirebase;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialPageConversationDetail.
 *
 * @package namespace App\Entities;
 */
class SocialPageConversationDetail extends BaseModel
{
    use SyncsWithFirebase;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_page_conversation_id',
        'provider_id',
        'message',
        'attachments',
        'sticker',
        'from',
    ];

    protected $casts = [
        'from' => 'array',
        'attachments' => 'array',
    ];

    public static function bootSyncsWithFirebase()
    {
    }

    public function conversation()
    {
        return $this->hasOne(SocialPageConversation::class, 'id', 'social_page_conversation_id');
    }
}
