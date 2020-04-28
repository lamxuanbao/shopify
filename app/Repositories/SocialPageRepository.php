<?php

namespace App\Repositories;

use App\Entities\SocialPage;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SocialPageRepository.
 *
 * @package namespace App\Repositories;
 */
class SocialPageRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialPage::class;
    }
}
