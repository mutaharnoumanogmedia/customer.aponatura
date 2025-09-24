<?php

namespace App\Jobs;

use App\Services\ManyChatClient;
use App\Services\MessageWindow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimitedWithRedis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batchable;


class SendPostToSubscriber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 5; // for transient 429s etc.

    public function __construct(
        public string $subscriberId,
        public string $channel, // 'wa','fb','ig'
        public array $payload   // post variables
    ) {}

    /** Optional: global throttle if you run Redis. */
    public function middleware(): array
    {
        $rps = max(1, (int) config('services.manychat.rps', 8));
        // This limiter key must be defined in AppServiceProvider (below)
        return [new RateLimitedWithRedis("manychat-send-rps-{$rps}")];
    }

    public function handle(ManyChatClient $mc): void
    {
        // 1) Live lookup
        $info = $mc->getSubscriberInfo($this->subscriberId);
        if (!$info['ok']) {
            // On 429, release and retry; otherwise log and drop
            if ($info['status'] === 429) {
                $this->release(5);
                return;
            }
            Log::warning('getInfo failed', ['sid' => $this->subscriberId, 'status' => $info['status']]);
            echo 'Not in 24-hour window';
            return;
        }

        // 2) Decide window
        $decision = $info['json']['data']['in_24_hours'] ?? false;
        $inside24h = $decision === true;

        // // 3) Choose flow
        // $flowNs = $inside24h
        //     ? (string) config('services.manychat.flow_std')
        //     : ($this->channel === 'wa'
        //         ? (string) config('services.manychat.flow_wa')   // WA template flow
        //         : (string) config('services.manychat.flow_std')); // FB/IG: your flow can branch to RN/Tag or you can decide to skip

        // if (!$flowNs) {
        //     Log::error('Flow NS not configured', ['channel' => $this->channel, 'sid' => $this->subscriberId]);
        //     return;
        // }

        // 4) Send


        $messages = [];

        // Add the post title as a text message
        if (!empty($this->payload['post_title'])) {
            $messages[] = [
                'type' => 'text',
                'text' => $this->payload['post_title'],
            ];
        }

        // Add each media item (image/video)
        if (!empty($this->payload['post_media']) && is_array($this->payload['post_media'])) {
            foreach ($this->payload['post_media'] as $mediaUrl) {
                // You'll need logic to determine if it's an image or a video.
                // For simplicity, we'll assume it's an image for this example.
                // You can add logic to check the file extension or MIME type.

                $messages[] = [
                    'type' => 'image',
                    'url'  => $mediaUrl,
                ];

                // Example for video:
                // $messages[] = [
                //     'type' => 'video',
                //     'url'  => $mediaUrl,
                // ];
            }
        }

        // Prepare the full content payload
        $content = [
            'version'  => 'v2',
            'messages' => $messages,
            'actions'  => [], // Optional, leave empty if not used
            'quick_replies' => [], // Optional, leave empty if not used
        ];

        $res = $mc->sendContent($this->subscriberId, $content);
        // $res = $mc->sendContent($this->subscriberId,  [
        //     'post_id'        => $this->payload['post_id']        ?? null,
        //     'post_title'     => $this->payload['post_title']     ?? null,
        //     'post_body'      => $this->payload['post_body']      ?? null,
        //     'post_image_url' => $this->payload['post_image_url'] ?? null,
        //     'post_url'       => $this->payload['post_url']       ?? null,
        //     'window_open'    => $inside24h,
        // ]);

        if (!$res['ok']) {
            if ($res['status'] === 429) {
                $this->release(5);
                return;
            }
            Log::warning('sendFlow failed', [
                'sid' => $this->subscriberId,
                'status' => $res['status'],
                'body' => $res['json'] ?? null,
            ]);
            echo 'Not in 24-hour window';
        }
    }
}
