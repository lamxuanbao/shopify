<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/13/20
 * Time: 15:10
 */

namespace App\Repositories;


use App\Entities\SocialPagePostComment;
use Prettus\Repository\Eloquent\BaseRepository;

class SocialPagePostCommentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialPagePostComment::class;
    }
}
