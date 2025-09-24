<?php

namespace App\Jobs;

use App\Models\ManychatSubscriber;
use App\Services\InstagramPostService;
use App\Services\ManyChatClient;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;


class BroadcastPostToAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $postId) {}

    public function handle(): void
    {
        $post = (new InstagramPostService())->getPostById($this->postId);
        if (!$post || !isset($post['id'])) {
            return;
        }




        $subscriberId = '1114999334';

        $jobs = [];
        ManychatSubscriber::query()
            ->whereNotNull('subscriber_id')
            ->where("subscriber_id", $subscriberId)
            ->select('id', 'subscriber_id')
            ->chunkById(1000, function ($chunk) use (&$jobs, $post) {
                foreach ($chunk as $row) {
                    $messages = collect(Arr::get($post, 'messages', []))
                        ->map(function ($m) {
                            return [
                                'caption'        => $m['caption'] ?? null,
                                'display_order'  => (int)($m['display_order'] ?? 0),
                                'media_url'      => $m['media_path'] ?? '',
                            ];
                        })
                        ->sortBy('display_order')  // optional: ensure order
                        ->values()
                        ->all();
                    $jobs[] = new SendPostToSubscriber(
                        subscriberId: (string) $row->subscriber_id,
                        channel: 'wa',
                        payload: $messages,
                    );
                }
            });

        Bus::batch($jobs)
            ->name('ManyChat Broadcast ' . $post["id"])
            ->allowFailures()
            ->then(function (Batch $batch) use ($post) {
                Log::info('Broadcast batch completed', ['post_id' => $post["id"], 'batch_id' => $batch->id]);
            })
            ->catch(function (Batch $batch, \Throwable $e) use ($post) {
                Log::error('Broadcast batch failed', ['post_id' => $post["id"], 'error' => $e->getMessage()]);
            })
            ->dispatch();
    }
}
