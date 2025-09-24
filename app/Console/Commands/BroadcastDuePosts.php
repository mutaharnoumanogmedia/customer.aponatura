<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Jobs\BroadcastPostToAll;
use App\Models\Post;
use App\Services\InstagramPostService;

class BroadcastDuePosts extends Command
{
    protected $signature = 'posts:broadcast-due';
    protected $description = 'Dispatch broadcasts for posts whose schedule time has arrived.';

    public function handle(): int
    {
        $now = Carbon::now();
        $due = (new InstagramPostService())->getTodayScheduledPosts();



        // Log $due to console
        // $this->info('Due posts: ' . json_encode($due));
        // return 0;
        foreach ($due as $p) {
            if (!isset($p["id"])) {
                \Log::error('BroadcastDuePosts: Post missing ID', ['post' => $p]);
                continue;
            }
            BroadcastPostToAll::dispatch($p["id"])->onQueue('high');
            \Log::info("Dispatched broadcast for post {$p["id"]}");
        }


        return self::SUCCESS;
    }
}
