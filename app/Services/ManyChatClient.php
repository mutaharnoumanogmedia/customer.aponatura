<?php

namespace App\Services;

use App\Jobs\SendPostToSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\ManyChatSubscriber;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ManyChatClient
{

    private string $base;
    private string $token;

    public function __construct()
    {
        $this->base  = rtrim(config('services.manychat.base', 'https://api.manychat.com'), '/');
        $this->token = (string) config('services.manychat.token');
    }

    protected function http()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->retry(2, 200, throw: false);
    }

    /** Get live info for one subscriber (includes system fields). */
    public function getSubscriberInfo(int|string $subscriberId): array
    {
        $resp = $this->http()->get("{$this->base}/fb/subscriber/getInfo", [
            'subscriber_id' => $subscriberId,
        ]);

        $data = ['ok' => $resp->ok(), 'status' => $resp->status(), 'json' => $resp->json()];

        if ($data['ok'] && isset($data['json']['data']['custom_fields'])) {
            $customFields = collect($data['json']['data']['custom_fields']);
            $lastInteractionField = $customFields->firstWhere('name', 'last_customer_interaction_timestamp');

            if ($lastInteractionField) {
                $lastInteractionTimestamp = $lastInteractionField['value'];
                $data['json']['data']['last_customer_interaction_timestamp'] = $lastInteractionTimestamp;

                try {
                    $lastInteractionTime = Carbon::parse($lastInteractionTimestamp);
                    $isWithin24Hours = $lastInteractionTime->diffInHours(Carbon::now()) < 24;
                    $data['json']['data']['in_24_hours'] = $isWithin24Hours;
                } catch (\Exception $e) {
                    $data['json']['data']['in_24_hours'] = false; // Or handle the error as needed
                }
            } else {
                $data['json']['data']['last_customer_interaction_timestamp'] = null;
                $data['json']['data']['in_24_hours'] = false;
            }
        }

        return $data;
    }

    /** Trigger a flow for one subscriber. */
    public function sendFlow(int|string $subscriberId, string $flowNs, array $data = []): array
    {
        $payload = [
            'subscriber_id' => $subscriberId,
            'flow_ns'       => $flowNs,
            'data'          => $data, // variables consumed by your Flow
        ];

        $resp = $this->http()->post("{$this->base}/fb/sending/sendFlow", $payload);

        return ['ok' => $resp->ok(), 'status' => $resp->status(), 'json' => $resp->json()];
    }


    public function sendContent(int|string $subscriberId, array $content): array
    {
        $payload = [
            'subscriber_id' => $subscriberId,
            'data'       => ['version' => 'v2', 'content' => $content],
            'message_tag' => "ACCOUNT_UPDATE",
            'otn_topic_name' => "wa"
        ];

        $resp = $this->http()->post("{$this->base}/fb/sending/sendContent", $payload);

        return ['ok' => $resp->ok(), 'status' => $resp->status(), 'json' => $resp->json()];
    }


    public function sendInsagramPostToSubscriber( $subscriberId,  $post): array
    {
        
    }
}
