<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/13/20
 * Time: 15:10
 */

namespace App\Repositories;


use App\Entities\SocialPagePost;
use Prettus\Repository\Eloquent\BaseRepository;

class SocialPagePostRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialPagePost::class;
    }
}
