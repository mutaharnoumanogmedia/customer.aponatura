<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class MessageWindow
{
    public static function evaluate(array $getInfoResponse): array
    {
        $data = $getInfoResponse['json']['data'] ?? $getInfoResponse['json'] ?? [];

        // Different accounts return slightly different keys — cover common cases:
        $segment = $data['messaging_window_segment']
            ?? $data['messagingWindowSegment']
            ?? null;

        $lastInteraction = $data['last_interaction']
            ?? $data['lastInteraction']
            ?? $data['lastInteractionDate']
            ?? null;

        // Prefer ManyChat’s own segment flag if present
        if (is_string($segment)) {
            $open = in_array(strtolower($segment), ['within_24h', 'inside_24h', 'open'], true);
            return ['open' => $open, 'reason' => "segment:$segment", 'last_interaction' => $lastInteraction];
        }

        // Fallback: compute from last_interaction timestamp
        if ($lastInteraction) {
            try {
                $diffH = Carbon::parse($lastInteraction)->diffInHours(now());
                return [
                    'open' => $diffH < 24,
                    'reason' => "last_interaction={$diffH}h",
                    'last_interaction' => (string) $lastInteraction,
                ];
            } catch (\Throwable $e) { /* ignore */
            }
        }

        // If unknown, treat as closed (safe)
        return ['open' => false, 'reason' => 'unknown', 'last_interaction' => null];
    }
}
