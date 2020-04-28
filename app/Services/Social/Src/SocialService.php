<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/7/20
 * Time: 10:51
 */

namespace App\Services\Social\Src;


use App\Repositories\SocialPageConversationDetailRepository;
use App\Repositories\SocialPageConversationRepository;
use App\Repositories\SocialPageConversationUserRepository;
use App\Repositories\SocialPageRepository;
use App\Repositories\SocialRepository;
use App\Repositories\UserRepository;
use App\Services\Facebook\FacebookFacade;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialService
{
    protected $userRepository;
    protected $socialRepository;
    protected $socialPageRepository;
    protected $socialPageConversationRepository;
    protected $socialPageConversationDetailRepository;
    protected $socialPageConversationUserRepository;

    public function __construct(
        UserRepository $userRepository,
        SocialRepository $socialRepository,
        SocialPageRepository $socialPageRepository,
        SocialPageConversationRepository $socialPageConversationRepository,
        SocialPageConversationDetailRepository $socialPageConversationDetailRepository,
        SocialPageConversationUserRepository $socialPageConversationUserRepository
    ) {
        $this->userRepository                         = $userRepository;
        $this->socialRepository                       = $socialRepository;
        $this->socialPageRepository                   = $socialPageRepository;
        $this->socialPageConversationRepository       = $socialPageConversationRepository;
        $this->socialPageConversationDetailRepository = $socialPageConversationDetailRepository;
        $this->socialPageConversationUserRepository   = $socialPageConversationUserRepository;
    }

    public function redirect($provider)
    {
        $social = Socialite::driver($provider);
        switch ($provider) {
            case 'facebook':
                $social->scopes(
                    [
                        'pages_messaging',
                        'manage_pages',
//                        'publish_pages',
                        'pages_show_list',
                        'email',
                    ]
                );
                break;
        }

        return $social->redirect();
    }

    public function auth($provider)
    {
        $getInfo = Socialite::driver($provider)
            ->user();
        $params  = [
            'provider'      => $provider,
            'provider_id'   => $getInfo->id,
            'token'         => $getInfo->token,
            'refresh_token' => $getInfo->refreshToken,
            'expires_in'    => $getInfo->expiresIn,
        ];
        $user    = $this->userRepository->find(Auth::user()->id);
        $social  = $user->social()
            ->provider($provider)
            ->updateOrCreate(
                [
                    'provider'    => $provider,
                    'provider_id' => $getInfo->id,
                ],
                $params
            );
        switch ($provider) {
            case 'facebook':
                $pages = FacebookFacade::getPages($social->provider_id, $social->token);
                if ($pages['status']) {
                    $facebookPages = $pages['data']['data'] ?? [];
                    $this->socialPageRepository->deleteWhere(['social_id' => $social->id]);
                    foreach ($facebookPages as $page) {
                        $this->socialPageRepository->withTrashed()
                            ->where('social_id', $social->id)
                            ->where('provider_id', $page['id'])
                            ->restore();
                        FacebookFacade::subscribedApps($page['id'], $page['access_token']);
                        $this->socialPageRepository->updateOrCreate(
                            [
                                'social_id'   => $social->id,
                                'provider_id' => $page['id'],
                            ],
                            [
                                'name'         => $page['name'],
                                'access_token' => $page['access_token'],
                                'provider_id'  => $page['id'],
                            ]
                        );
                        FacebookFacade::saveConversations($page['id'], $page['access_token']);
                    }
                }
                break;
        }
    }
}
