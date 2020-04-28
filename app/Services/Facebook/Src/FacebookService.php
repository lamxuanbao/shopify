<?php
/**
 * Created by PhpStorm.
 * User: nhockizi
 * Date: 4/6/20
 * Time: 15:52
 */

namespace App\Services\Facebook\Src;

use App\Entities\SocialPage;
use App\Entities\SocialPageConversation;
use App\Entities\SocialPageConversationDetail;
use App\Repositories\SocialPageConversationDetailAttachmentRepository;
use App\Repositories\SocialPageConversationDetailRepository;
use App\Repositories\SocialPageConversationRepository;
use App\Repositories\SocialPageConversationUserRepository;
use App\Repositories\SocialPagePostCommentRepository;
use App\Repositories\SocialPagePostRepository;
use App\Repositories\SocialPageRepository;
use Facebook\Facebook;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    protected $facebook;
    protected $socialPageRepository;
    protected $socialPageConversationRepository;
    protected $socialPageConversationDetailRepository;
    protected $socialPageConversationUserRepository;
    protected $socialPagePostRepository;
    protected $socialPagePostCommentRepository;

    public function __construct(
        Facebook $facebook,
        SocialPageRepository $socialPageRepository,
        SocialPageConversationRepository $socialPageConversationRepository,
        SocialPageConversationDetailRepository $socialPageConversationDetailRepository,
        SocialPageConversationUserRepository $socialPageConversationUserRepository,
        SocialPagePostRepository $socialPagePostRepository,
        SocialPagePostCommentRepository $socialPagePostCommentRepository
    ) {
        $this->facebook                               = $facebook;
        $this->socialPageRepository                   = $socialPageRepository;
        $this->socialPageConversationRepository       = $socialPageConversationRepository;
        $this->socialPageConversationDetailRepository = $socialPageConversationDetailRepository;
        $this->socialPageConversationUserRepository   = $socialPageConversationUserRepository;
        $this->socialPagePostRepository               = $socialPagePostRepository;
        $this->socialPagePostCommentRepository        = $socialPagePostCommentRepository;
    }

    public function getPages($pageId, $accessToken)
    {
        return $this->getRequest("{$pageId}/accounts", $accessToken);
    }

    public function subscribedApps($pageId, $accessToken)
    {
        $query = ['subscribed_fields' => 'messages,message_deliveries,feed'];

        return $this->postRequest("{$pageId}/subscribed_apps", $accessToken, $query);
    }

    public function getConversations($pageId, $pageToken, array $query = [])
    {
        return $this->getRequest("{$pageId}/conversations", $pageToken, $query);
    }

    public function saveConversations(
        $pageId,
        $pageToken,
        array $query = ['fields' => 'subject,messages{from,to,message,attachments,sticker},senders,link']
    ) {
        $conversations = $this->getConversations($pageId, $pageToken, $query);
        if ($conversations['status']) {
            $socialPage = $this->socialPageRepository->findWhere(['provider_id' => $pageId])
                ->first();
            $this->socialPageConversationRepository->deleteWhere(
                [
                    'social_page_id' => $socialPage->id,
                ]
            );
            //                $this->socialPageConversationDetailRepository->deleteWhere(
            //                    [
            //                        'social_page_conversation_id' => $conversationData->id,
            //                    ]
            //                );
            foreach ($conversations['data']['data'] as $conversation) {
                $conversationData = $this->socialPageConversationRepository->create(
                    [
                        'social_page_id' => $socialPage->id,
                        'provider_id'    => $conversation['id'],
                        'link'           => $conversation['link'],
                    ]
                );
                $messages         = $conversation['messages']['data'] ?? [];
                $i                = count($messages);
                while ($i > 0) {
                    $i--;
                    $message = $messages[$i];
                    $this->socialPageConversationDetailRepository->create(
                        [
                            'social_page_conversation_id' => $conversationData->id,
                            'provider_id'                 => $message['id'],
                            'message'                     => $message['message'],
                            'from'                        => $message['from'],
                            'sticker'                     => $message['sticker'] ?? null,
                            'attachments'                 => $message['attachments']['data'] ?? [],
                        ]
                    );
                }
                $senders = $conversation['senders']['data'] ?? [];
                foreach ($senders as $sender) {
                    $this->socialPageConversationUserRepository->updateOrCreate(
                        [
                            'social_page_conversation_id' => $conversationData->id,
                            'provider_id'                 => $sender['id'],
                        ],
                        [
                            'social_page_conversation_id' => $conversationData->id,
                            'provider_id'                 => $sender['id'],
                            'name'                        => $sender['name'],
                            'avatar'                      => 'ssss',
                        ]
                    );
                }
            }
        }
    }

    public function getInfo($pageId, $pageToken, array $query = [])
    {
        return $this->getRequest("{$pageId}", $pageToken, $query);
    }

    public function getPosts($pageId, $pageToken, array $query = [])
    {
        return $this->getRequest("{$pageId}/posts", $pageToken, $query);
    }

    public function savePosts(
        $socialPageId,
        $pageId,
        $pageToken,
        array $query = []
    ) {
        $posts = $this->getPosts($pageId, $pageToken, $query);
        if ($posts['status']) {
            foreach ($posts['data']['data'] as $post) {
                if (isset($post['message'])) {
                    $post = $this->socialPagePostRepository->updateOrCreate(
                        [
                            'social_page_id' => $socialPageId,
                            'provider_id'    => $post['id'],
                        ],
                        [
                            'social_page_id' => $socialPageId,
                            'message'        => $post['message'],
                            'provider_id'    => $post['id'],
                        ]
                    );
                    $this->saveCommets($post->id, $post->provider_id, $pageToken);
                }
            }
        }
    }

    public function getComments($postId, $pageToken, array $query = [])
    {
        return $this->getRequest("{$postId}/comments", $pageToken, $query);
    }

    public function saveCommets(
        $socialPagePostId,
        $postId,
        $pageToken,
        array $query = []
    ) {
        $comments = $this->getComments($postId, $pageToken, $query);
        if ($comments['status']) {
            foreach ($comments['data']['data'] as $comment) {
                if (isset($comment['message'])) {
                    $this->socialPagePostCommentRepository->updateOrCreate(
                        [
                            'social_page_post_id' => $socialPagePostId,
                            'provider_id'         => $comment['id'],
                        ],
                        [
                            'social_page_post_id' => $socialPagePostId,
                            'message'             => $comment['message'],
                            'provider_id'         => $comment['id'],
                        ]
                    );
                }
            }
        }
    }

    public function replyComment($commentId, $pageToken, array $query)
    {
        return $this->postRequest("{$commentId}/comments", $pageToken, $query);
    }

    public function getMessageInfo($messageId, $pageToken)
    {
        $query = [
            "fields" => "sticker,tags,attachments,shares,from,id,message,to",
        ];

        return $this->getRequest("{$messageId}", $pageToken, $query);
    }

    private function saveMessage($data, $sync = false)
    {
        Log::info('message '.print_r($data, true));
        $message = $this->socialPageConversationDetailRepository->create($data);
        if ($sync) {
            $message->saveToFirebase('set');
        }

        return $message;
    }

    private function checkMessage($mid, $conversationId, $accessToken)
    {
        $messageInfo = $this->getMessageInfo($mid, $accessToken);
        if ($messageInfo['status']) {
            $conversationDetail = $this->socialPageConversationDetailRepository->findWhere(
                [
                    'social_page_conversation_id' => $conversationId,
                    'provider_id'                 => $mid,
                ]
            )
                ->first();
            $sync               = false;
            if (!isset($conversationDetail->id)) {
                $sync = true;
            }
            $data        = $messageInfo['data'];
            $attachments = [];
            if (isset($data['attachments'])) {
                $attachments = $data['attachments']['data'] ?? $data['attachments'];
            }
            $this->saveMessage(
                [
                    'social_page_conversation_id' => $conversationId,
                    'provider_id'                 => $data['id'],
                    'message'                     => $data['message'] ?? null,
                    'from'                        => $data['from'],
                    'sticker'                     => $data['sticker'] ?? null,
                    'attachments'                 => $attachments,
                ],
                $sync
            );
        }
    }

    private function messageHook(SocialPage $page, array $messages)
    {
        foreach ($messages as $message) {
            $userId = $message['sender']['id'];
            $conversation = $page->conversations()
                ->select('social_page_conversations.*')
                ->join(
                    'social_page_conversation_users',
                    function ($join) use ($userId) {
                        $join->on(
                            'social_page_conversation_users.social_page_conversation_id',
                            '=',
                            'social_page_conversations.id'
                        )
                            ->where('social_page_conversation_users.provider_id', $userId);
                    }
                )
                ->first();
            if (isset($message['message'])) {
                $this->checkMessage($message['message']['mid'], $conversation->id, $page->access_token);
            } else {
                if (isset($message['delivery'])) {
                    foreach ($message['delivery']['mids'] as $mid) {
                        $this->checkMessage($mid, $conversation->id, $page->access_token);
                    }
                }
            }
        }
    }

    private function feedHook($accessToken, $item)
    {
        if (isset($item['changes'])) {
            foreach ($item['changes'] as $changeItem) {
                $value = $changeItem['value'];
                switch ($value['item']) {
                    case 'status':

                        break;
                    case 'comment':
                        if ($item['id'] != $value['from']['id']) {
                            $params = [
                                $value['comment_id'],
                                $accessToken,
                                [
                                    'message' => 'auto reply',
                                ],
                            ];
                            Log::info(print_r($params, true));
                            $a = $this->replyComment(
                                $value['comment_id'],
                                $accessToken,
                                [
                                    'message' => 'auto reply',
                                ]
                            );
                            Log::info('reply Comment : '.print_r($a, true));
                        };
                        break;
                }
            }
        }
    }

    public function webHook(array $params = [])
    {
        try {
            $entry = $params['entry'] ?? [];
            Log::info('messageHook '.print_r($params, true));
            foreach ($entry as $item) {
                $page = $this->socialPageRepository->findWhere(['provider_id' => $item['id']])
                    ->first();
                if (isset($page->id)) {
                    $this->messageHook($page, $item['messaging'] ?? []);
                    $this->feedHook($page->access_token, $item);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getFile().' '.$e->getLine());
        }
    }

    private function getRequest($endpoint, $accessToken, array $query = [])
    {
        $i = 0;
        if (count($query) > 0) {
            foreach ($query as $key => $value) {
                if ($i == 0) {
                    $endpoint .= '?';
                } else {
                    $endpoint .= '&';
                }
                $endpoint .= "{$key}={$value}";
                $i++;
            }
        }
        try {
            $result = $this->facebook->get(
                $endpoint,
                $accessToken
            )
                ->getDecodedBody();

            return [
                'status' => true,
                'data'   => $result,
            ];
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            Log::error('Graph returned an error: '.$e->getMessage());

            return ['status' => false, 'message' => 'Graph returned an error: '.$e->getMessage(),];
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: '.$e->getMessage());

            return ['status' => false, 'message' => 'Facebook SDK returned an error: '.$e->getMessage(),];
        }
    }

    private function postRequest($endpoint, $accessToken, array $query = [])
    {
        try {
            $result = $this->facebook->post(
                $endpoint,
                $query,
                $accessToken
            )
                ->getDecodedBody();

            return [
                'status' => true,
                'data'   => $result,
            ];
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            Log::error('Graph returned an error: '.$e->getMessage());

            return ['status' => false, 'message' => 'Graph returned an error: '.$e->getMessage(),];
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: '.$e->getMessage());

            return ['status' => false, 'message' => 'Facebook SDK returned an error: '.$e->getMessage(),];
        }
    }

    public function sendMessage($accessToken, array $query = [])
    {
        return $this->postRequest("me/messages", $accessToken, $query);
    }

    public function getFeeds($pageId, $accessToken)
    {
        return $this->getRequest("{$pageId}/feed", $accessToken);
    }
}
