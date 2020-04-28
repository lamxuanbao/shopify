<?php

namespace App\Repositories;

use App\Entities\SocialPageConversationDetail;
use App\Entities\SocialPageConversationUser;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SocialPageConversationUserRepository.
 *
 * @package namespace App\Repositories;
 */
class SocialPageConversationUserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialPageConversationUser::class;
    }
}
