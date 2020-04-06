<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/3/20
 * Time: 14:36
 */

namespace App\Repositories;


use App\Entities\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }
}
