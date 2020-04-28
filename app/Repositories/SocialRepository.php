<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/7/20
 * Time: 10:56
 */

namespace App\Repositories;


use App\Entities\Social;
use Prettus\Repository\Eloquent\BaseRepository;

class SocialRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Social::class;
    }
}
