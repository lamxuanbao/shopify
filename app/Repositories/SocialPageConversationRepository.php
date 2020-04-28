<?php

namespace App\Repositories;

use App\Entities\SocialPageConversation;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SocialPageConversationRepository.
 *
 * @package namespace App\Repositories;
 */
class SocialPageConversationRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialPageConversation::class;
    }
}
