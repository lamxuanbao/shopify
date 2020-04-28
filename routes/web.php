<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Repositories\SocialPageRepository;
use Illuminate\Http\Request;
use App\Entities\SocialPageConversationDetail;
use App\Entities\SocialPage;
use App\Entities\SocialPagePost;
use App\Entities\SocialPageConversation;
use App\Entities\SocialPageConversationUser;
use App\Services\Facebook\FacebookFacade;
use App\Entities\SocialPagePostComment;
use Illuminate\Support\Facades\Response;

Route::get(
    '/',
    function () {
        return view('app');
    }
);
Route::get(
    '/cache',
    function () {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        return "Cache is cleared";
    }
);
Route::get(
    "/callback/facebook/message",
    'SocialController@fbMessageHook'
)
    ->middleware(\App\Http\Middleware\FacebookVerify::class);
Route::post(
    "/callback/facebook/message",
    'SocialController@fbMessageHook'
);
Route::get('/test', 'TestController');
Route::get('/social/{provider}', ['as' => 'social', 'uses' => 'SocialController@redirect']);
Route::get('/callback/{provider}', 'SocialController@callback');
Route::post('/install', ['as' => 'install', 'uses' => 'ShopifyController@install']);
Route::get('/auth', 'ShopifyController@auth');
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/message/create', ['as' => 'postMessage', 'uses' => 'ProductMessageController@store']);
Route::get('/product/{slug}', ['as' => 'productDetail', 'uses' => 'ProductController@detail']);
Route::get(
    '/facebook/page/{pageId}',
    [
        'as'   => 'facebookPage',
        'uses' => function ($pageId) {
            return view('facebook_page')->with(['pageId' => $pageId]);
        },
    ]
);
Route::get(
    '/message/{pageId}/{conversasionId}',
    [
        'as'   => 'messageDetail',
        'uses' => function ($pageId, $conversasionId) {
            $page = SocialPage::where('provider_id', $pageId)
                ->first();
            $messages = SocialPageConversationDetail::where(
                'social_page_conversation_id',
                $conversasionId
            )
                ->get();
            $user     = SocialPageConversationUser::where(
                'social_page_conversation_id',
                $conversasionId
            )
                ->where('provider_id', '<>', $page->provider_id)
                ->first();
            $view     = view(
                'partial._message',
                [
                    'pageId'   => $pageId,
                    'messages' => $messages,
                    'user' => $user,
                ]
            )->render();

            return Response::json(['status' => 200, 'view' => $view]);
        },
    ]
);
Route::post(
    '/message/{pageId}/{conversasionId}',
    [
        'as'   => 'messageDetail',
        'uses' => function ($pageId, $conversasionId, Request $request) {
            $params = $request->all();
            if (isset($params['message']) && !is_null($params['message'])) {
                $page = SocialPage::where('provider_id', $pageId)
                    ->first();

                $user = SocialPageConversationUser::select('social_page_conversation_users.*')
                    ->join(
                        'social_page_conversations',
                        function ($join) use ($page) {
                            $join->on(
                                'social_page_conversations.id',
                                '=',
                                'social_page_conversation_users.social_page_conversation_id'
                            )
                                ->where('social_page_conversation_users.provider_id', '<>', $page->provider_id);
                        }
                    )
                    ->where(
                        'social_page_conversation_id',
                        $conversasionId
                    )
                    ->first();

                return FacebookFacade::sendMessage(
                    $page->access_token,
                    [
                        "recipient" => [
                            "id" => (string)$user->provider_id,
                        ],
                        "message"   => [
                            'text' => $params['message'],
                        ],
                    ]
                );
            }
        },
    ]
);

Route::get(
    '/post/{postId}',
    [
        'as'   => 'postDetail',
        'uses' => function ($postId) {
            return view('post_detail')->with(['postId' => $postId]);
        },
    ]
);
Route::post(
    '/comment/{commentId}',
    [
        'as'   => 'replyComment',
        'uses' => function ($commentId, Request $request) {
            $params      = $request->all();
            $comment     = SocialPagePostComment::where('provider_id', $commentId)
                ->first();
            $accessToken = $comment->post->page->access_token;
            FacebookFacade::replyComment(
                $commentId,
                $accessToken,
                $params
            );

            return redirect()->back();
        },
    ]
);
