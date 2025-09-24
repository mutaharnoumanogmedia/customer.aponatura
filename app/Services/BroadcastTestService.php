<?php

namespace App\Services;

use App\Jobs\SendPostToSubscriber;
use App\Models\ManychatSubscriber;
use App\Services\InstagramPostService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;


class BroadcastTestService
{
    public function testBroadcast(int $postId): array
    {
        $post = (new InstagramPostService())->getPostById($postId);
        if (!$post) {
            return [];
        }


        $jobs = [];

        ManychatSubscriber::query()
            ->whereNotNull('subscriber_id')
            ->where("subscriber_id", '1114999334')
            ->select('id', 'subscriber_id')
            ->chunkById(1000, function (Collection $chunk) use (&$jobs, $post) {

                $messages = collect(Arr::get($post, 'messages', []))
                    ->map(function ($m) {
                        return [
                            'caption'        => $m['caption'] ?? null,
                            'display_order'  => (int)($m['display_order'] ?? 0),
                            'media_url'      => $m['media_path'],
                        ];
                    })
                    ->sortBy('display_order')  // optional: ensure order
                    ->values()
                    ->all();


                foreach ($chunk as $row) {
                    echo $row->subscriber_id;
                    // $jobs[] = new SendPostToSubscriber(
                    //     subscriberId: (string) $row->subscriber_id,
                    //     channel: 'wa',
                    //     payload: [
                    //         'post_title' => $post["caption"],
                    //         'post_media' => $post["media_paths"],
                    //     ],
                    // );


                    $job[] = $this->sendPostToSubscriber(
                        (string) $row->subscriber_id,
                        $messages
                    );
                }
            });

        return $jobs;
    }




    public function sendPostToSubscriber(string $subscriberId, array $payload)
    {
        $mc = new ManyChatClient();

        // 1) Live lookup to check 24-hour window
        $info = $mc->getSubscriberInfo($subscriberId);

        if (!$info['ok']) {
            // Log and drop on failure, except for rate limits
            if ($info['status'] !== 429) {
                Log::warning('getInfo failed', ['sid' => $subscriberId, 'status' => $info['status']]);
            }
            return;
        }

        // 2) Decide if within 24-hour window
        $inside24h = $info['json']['data']['in_24_hours'] ?? false;

        if ($inside24h) {
            // 3) Conditionally build message content
            $messages = [];

            // Add the post title as a text message
            if (!empty($payload['post_title'])) {
                $messages[] = [
                    'type' => 'text',
                    'text' => $payload['post_title'],
                ];
            }

            // Add each media item (image/video) if within the 24-hour window
            if (!empty($payload['post_media']) && is_array($payload['post_media'])) {
                foreach ($payload['post_media'] as $mediaUrl) {
                    // Placeholder logic to determine media type
                    $extension = pathinfo($mediaUrl, PATHINFO_EXTENSION);
                    $type = in_array($extension, ['mp4', 'mov', 'webm']) ? 'video' : 'image';

                    $messages[] = [
                        'type' => $type,
                        'url'  => $mediaUrl,
                    ];
                }
            }

            // 4) Send the content
            $content = [
                'messages' => $messages,
                'actions' => [],
                'quick_replies' => [],
            ];

            // Send the content only if there are messages to send
            if (!empty($messages)) {
                $res = $mc->sendContent($subscriberId, $content);
                if (!$res['ok'] && $res['status'] !== 429) {
                    Log::warning('sendContent failed', [
                        'sid' => $subscriberId,
                        'status' => $res['status'],
                        'body' => $res['json'] ?? null,
                    ]);
                    echo 'Not in 24-hour window';
                } else {
                    Log::info('sendContent succeeded', [
                        'sid' => $subscriberId,
                        'status' => $res['status'],
                        'body' => $res['json'] ?? null,
                    ]);
                    echo 'Message sent in 24-hour window';
                }
            }
        } else {
            Log::info('Subscriber is not in 24-hour window', ['sid' => $subscriberId]);
            echo 'Not in 24-hour window';
            return;
        }
    }
}
