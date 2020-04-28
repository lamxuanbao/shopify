<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/6/20
 * Time: 13:41
 */

namespace App\Http\Controllers;


use App\Entities\SocialPage;
use App\Entities\SocialPageConversationUser;
use App\Entities\User;
use App\Http\Middleware\Facebook;
use App\Repositories\SocialPageConversationDetailRepository;
use App\Repositories\SocialPageConversationRepository;
use App\Repositories\SocialPageConversationUserRepository;
use App\Repositories\SocialPageRepository;
use App\Services\Facebook\FacebookFacade;
use App\Services\FacebookService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    protected $page;

    public function __construct()
    {
        $this->page = SocialPage::find(1);
    }

    private function getConvertsations()
    {
        $query  = [
            'fields' => 'subject,messages{from,to,message},senders,link',
        ];
        $result = FacebookFacade::getConversations(
            $this->page->provider_id,
            $this->page->access_token,
            $query
        );

        return $result;
    }

    private function getMessageInfo($id)
    {
        $page = SocialPage::find(1)
            ->toArray();

        return FacebookFacade::getMessageInfo($id, $page['access_token']);
    }

    private function getConversations()
    {
        $page = SocialPage::find(1)
            ->toArray();
        FacebookFacade::saveConversations($page['provider_id'], $page['access_token']);
    }

    private function getPosts()
    {
        $result = FacebookFacade::savePosts(
            $this->page->id,
            $this->page->provider_id,
            $this->page->access_token
        );

        return $result;
    }

    private function replyComment()
    {
        $result = FacebookFacade::replyComment(
            '112318060447963_112375677108868',
            'EAAmI0sjj8q8BAIYs3QtNeqnjQmnEZAHuB3fcYZCqyMZC0Rbl8hzRGg59qMr4ZC3JUYDIfiONSSvjwhpOrNVocLPZB5ToAuMkJ4NoOX794zc5yqbKZAvATjPQqLZBBZCwiKCLHyxXynQPZAZAPNZAvOohXn3LiaZAmOxHSlq34HGGHZCeh3YKN4BWfZA9VBaW4ClePvY1YZD',
            [
                'message' => 'This is a test comment',
            ]
        );

        return $result;
    }

    public function __invoke()
    {
        $session = DB::getMongoClient()
            ->startSession();
        $session->startTransaction([]);
        try {
            User::create(
                [
                    'email' => 'bao@gmail.com',
                ]
            );
            throw new \Exception('asdasd');
            $session->commitTransaction();
            dd('asd');
        } catch (\Exception $e) {
            $session->abortTransaction();
            dd('error');
        }
        dd('sss');
        $post = $this->replyComment();
        dd($post);
        //        //        $result = $this->getConvertsations();
        //        $result = $this->getMessageInfo(
        //            'm_MVRpnSMky3_-Tw04kSAQktoOxLDylpoIkiOK1F3_YJVuzqRdxw2r6ADEU7NxtpJvYh9lXIPaP_KfKGRhAknYGA'
        //        );
        //        //        $result = $this->replyComment('1935675649897834_1935735853225147');
        //
        //        dd($result);
        $params  = [
            'object' => 'page',
            'entry'  => [
                [
                    'id'        => '685074164957995',
                    'time'      => '1586750838042',
                    'messaging' => [
                        [
                            'sender'    => [
                                'id' => '3567088776640353',
                            ],
                            'recipient' => [
                                'id' => '685074164957995',
                            ],
                            'timestamp' => '1586750837880',
                            'message'   => [
                                [
                                    'mid'  => 'm_WlhnlWFFji6SMHwp-ZJCftoOxLDylpoIkiOK1F3_YJWqy5phdCHesh7a_cw-yeBJpDdQOJ0z27MR-G3Y6KBEdA',
                                    'text' => '2',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $message = FacebookFacade::webHook($params);
        dd($message);
        //        $feeds   = FacebookFacade::getFeeds($this->page->provider_id, $this->page->access_token);
        $message = FacebookFacade::sendMessage(
            $this->page->access_token,
            [
                "recipient" => [
                    "id" => '2934272149991779',
                ],
                "message"   => [
                    'text' => 'test',
                ],
            ]
        );
        dd($message);
        //
        ////        $user     = User::find(Auth::user()->id);
        ////        $facebook = $user->social()
        ////            ->provider('facebook')
        ////            ->first();
        $pages = FacebookFacade::getPages();
        dd($pages);
        //        //        $a = FacebookFacade::test();
        //
        //        //        dd($a);
        //        //        $scriptTags = [
        //        //            'script_tag' => [
        //        //                "event" => "onload",
        //        //                "src"   => ShopifyService::generateScript(),
        //        //            ],
        //        //        ];
        //        //        dd($scriptTags);
        //        $id          = '119733911611';
        //        $accessToken = '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';
        //        $client      = new Client();
        //        try {
        //            $response = $client->request(
        //                'DELETE',
        //                "https://lamxuanbao.myshopify.com/admin/api/2020-04/script_tags/{$id}.json",
        //                [
        //                    'headers' => [
        //                        'Content-Type'           => 'application/json',
        //                        'X-Shopify-Access-Token' => $accessToken,
        //                    ],
        //                ]
        //            );
        //
        //            return [
        //                'status' => true,
        //                'data'   => json_decode(
        //                    $response->getBody()
        //                        ->getContents(),
        //                    true
        //                ),
        //            ];
        //        } catch (\Exception $exception) {
        //            return [
        //                'status'  => false,
        //                'message' => $exception->getMessage(),
        //            ];
        //        }
    }
}
