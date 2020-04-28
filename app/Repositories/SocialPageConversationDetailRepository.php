<?php

namespace App\Repositories;

use App\Entities\SocialPageConversationDetail;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SocialPageConversationDetailRepository.
 *
 * @package namespace App\Repositories;
 */
class SocialPageConversationDetailRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialPageConversationDetail::class;
    }
}
