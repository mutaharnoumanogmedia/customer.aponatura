<?php

namespace App\Http\Controllers\API;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WaLinkController extends Controller
{
    public function start(Request $request)
    {
        $email = trim((string) $request->input('email'));
        if ($email === '') {
            return response()->json(['error' => 'Email is required'], 400);
        }

        // Optional filters
        $startDate = $request->input('start_date');   // e.g. 2019-01-01
        $endDate   = $request->input('end_date');     // e.g. 2020-12-31

        // Pagination (WhatsApp-friendly defaults)
        $page    = max(1, (int) $request->input('page', 1));
        $perPage = max(1, min(5, (int) $request->input('per_page', 5)));

        // Resolve customer
        $customerResp = (new CustomerService())->getCustomerByEmail($email)->getData(true);
        if (empty($customerResp['customer'])) {

            return response()->json([
                'success'       => false,
                'found'         => false,
                'message'       => 'customer not found',
                'customer'      => null,

                // current page slice
                'orders'        => [],
                'orders_json'   => "",
                //json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'orders_count'  => 0,

                // latest order (ManyChat-friendly)
                'latest_order_number'        => null,
                'latest_order_status'        => null,
                'latest_order_tracking_url'  => null,
                'latest_order_date'          => null,
                'latest_order_iso'           => null,

                // paging meta
                'page'          => $page,
                'per_page'      => $perPage,
                'has_more'      => false,

                // echo the effective range used (handy for debugging in ManyChat)
                'range'         => [
                    'start' => $this->resolveDateRange($startDate, $endDate)[0]->toIso8601String(),
                    'end'   => $this->resolveDateRange($startDate, $endDate)[1]->toIso8601String(),
                ],
            ]);
        }
        $customer = $customerResp['customer'];

        // Get and filter all orders (your helper already handles date range + decorations)
        $allOrders = $this->getCustomerOrdersByEmail($customer['email'], $startDate, $endDate);
        $total     = count($allOrders);

        // Paginate
        $orders = collect($allOrders)->forPage($page, $perPage)->values()->all();
        $hasMore = $total > $page * $perPage;

        // pick the latest order from the current page slice, or fall back to the overall latest
        $latestOrder = $orders[0] ?? ($allOrders[0] ?? null);

        // prepare scalar helpers for ManyChat
        $latest_order_number       = $latestOrder['bestellNr']   ?? null;
        $latest_order_status       = isset($latestOrder['statusLabel']) ? $latestOrder['statusLabel'] : null;
        $latest_order_tracking_url = $latestOrder['tracking_url'] ?? null;

        // dates (your helper already adds datum_human / datum_iso; if not present, build from UNIX timestamp)
        $latest_order_date_human = $latestOrder['datum_human']
            ?? (isset($latestOrder['datum']) && is_numeric($latestOrder['datum'])
                ? \Carbon\Carbon::createFromTimestamp((int)$latestOrder['datum'])->format('Y-m-d')
                : null);

        $latest_order_age = $latest_order_date_human ? Carbon::parse($latest_order_date_human)->diffInDays(now()) : null;

        $latest_order_date_iso = $latestOrder['datum_iso']
            ?? (isset($latestOrder['datum']) && is_numeric($latestOrder['datum'])
                ? \Carbon\Carbon::createFromTimestamp((int)$latestOrder['datum'])->toIso8601String()
                : null);

        // stringified object for optional mapping
        $latest_order_json = $latestOrder
            ? json_encode($latestOrder, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : null;

        $latest_order_title = $latestOrder['orderTitle'] ?? null;

        // For ManyChat mapping (stringified array + counters)
        return response()->json([
            'success'       => true,
            'found'         => true,
            'message'       => $total ? 'customer found' : 'customer found, no orders in range',
            'customer'      => $customer,

            // current page slice
            'orders'        => $orders,
            'orders_json'   => json_encode($orders, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'orders_count'  => $total,

            // latest order (ManyChat-friendly)
            'latest_order_number'        => $latest_order_number,
            'latest_order_status'        => $latest_order_status,
            'latest_order_tracking_url'  => $latest_order_tracking_url,
            'latest_order_date'          => $latest_order_date_human,
            'latest_order_age'           => $latest_order_age,
            'latest_order_title'        => $latest_order_title,
            'latest_order_iso'           => $latest_order_date_iso,

            // paging meta
            'page'          => $page,
            'per_page'      => $perPage,
            'has_more'      => $hasMore,

            // echo the effective range used (handy for debugging in ManyChat)
            'range'         => [
                'start' => $this->resolveDateRange($startDate, $endDate)[0]->toIso8601String(),
                'end'   => $this->resolveDateRange($startDate, $endDate)[1]->toIso8601String(),
            ],
        ]);
    }


    protected function getCustomerOrdersByEmail($customer_email, ?string $startDate = null, ?string $endDate = null): array
    {
        $resp = Http::withoutVerifying()
            ->get(env('APP_WARENSYS') . "/api/customer-portal/customer-orders/{$customer_email}");

        if (!$resp->successful()) {
            Log::warning('customer-orders API error', ['email' => $customer_email, 'status' => $resp->status()]);
            return [];
        }

        $orders = $resp->json('orders', []);
        [$start, $end] = $this->resolveDateRange($startDate, $endDate);

        $orders = collect($orders)
            ->filter(function ($order) use ($start, $end) {
                $date = $this->parseOrderDate($order);
                // include boundaries (>= start && <= end)
                return $date ? $date->between($start, $end, true) : false;
            })
            ->sortByDesc(function ($order) {
                $date = $this->parseOrderDate($order);
                return $date ? $date->getTimestamp() : 0;
            })
            ->values()
            ->map(function ($order) {
                // valid_for_review
                $status = isset($order['status']) ? (int) $order['status'] : null;
                $meetsStatus = $status !== null
                    && in_array($status, OrderStatus::forCategory('completed'), true);

                $date = $this->parseOrderDate($order);
                $olderThan7Days = $date ? $date->diffInDays(now()) >= 7 : false;

                $order['valid_for_review'] = ($meetsStatus || $olderThan7Days);

                // Add readable dates
                if ($date) {
                    $order['datum_iso']   = $date->toIso8601String();
                    $order['datum_human'] = $date->format('Y-m-d');
                }

                return $order;
            })
            ->all();

        return $orders;
    }


    public function getOrderByOrderNumber(Request $request, $order_number)
    {
        $startDate = $request->input('start_date'); // optional
        $endDate   = $request->input('end_date');   // optional


        $customer_email = $request->input('customer_email'); //optional

        if ($customer_email) {
            $resp = Http::withoutVerifying()
                ->get(env('APP_WARENSYS') . "/api/customer-portal/order/{$order_number}", [
                    'customer_email' => $customer_email
                ]);
        } else {
            $resp = Http::withoutVerifying()
                ->get(env('APP_WARENSYS') . "/api/customer-portal/order/{$order_number}");
        }




        if (!$resp->successful()) {
            return response()->json([
                'success' => false,
                'found'   => false,
                'message' => 'Unable to fetch order from API',
                'order_number'           => null,
                'order_status'           => null,
                'order_tracking_url'     => null,
                'order_date'             => null,
                'order_age'              => null,
                'order_iso'              => null,
                'order_title'            => null,
                'shipments_count'        => 0,
                'shipments_json'         => "",
                'order'                  => null,
                'shipments'              => null, // remove if not needed
            ], 200);
        }

        $payloadOrder = $resp->json('order') ?? [];
        if (empty($payloadOrder)) {
            return response()->json([
                'success' => false,
                'found'   => false,
                 'message' => 'Order not found',
                'order_number'           => null,
                'order_status'           => null,
                'order_tracking_url'     => null,
                'order_date'             => null,
                'order_age'              => null,
                'order_iso'              => null,
                'order_title'            => null,
                'shipments_count'        => 0,
                'shipments_json'         => "",
                'order'                  => null,
                'shipments'              => null, // remove if not needed
            ], 200);
       
         }

        // Normalize to a shipments array
        $shipments = (is_array($payloadOrder) && function_exists('array_is_list') ? array_is_list($payloadOrder) : array_keys((array)$payloadOrder) === range(0, count((array)$payloadOrder) - 1))
            ? $payloadOrder
            : [$payloadOrder];

        // Optional date gating (if any shipment is in range we keep it; otherwise 404)
        if ($startDate || $endDate) {
            [$start, $end] = $this->resolveDateRange($startDate, $endDate);
            $inRange = collect($shipments)->contains(function ($s) use ($start, $end) {
                $ts = $s['versanddatum'] ?? $s['datum'] ?? null;
                if (is_numeric($ts)) {
                    $d = \Carbon\Carbon::createFromTimestamp((int)$ts);
                } else {
                    $d = $this->parseOrderDate($s);
                }
                return $d ? $d->between($start, $end, true) : false;
            });
            if (!$inRange) {
                return response()->json([
                    'success' => false,
                    'found'   => false,
 
                    'message' => 'Order not in requested date range',
                    'order_number'           => null,
                    'order_status'           => null,
                    'order_tracking_url'     => null,
                    'order_date'             => null,
                    'order_age'              => null,
                    'order_iso'              => null,
                    'order_title'            => null,
                    'shipments_count'        => 0,
                    'shipments_json'         => "",
                    'order'                  => null,
                    'shipments'              => null, // remove if not needed
                ], 200);
 
            }
        }

        // Decorate each shipment with readable dates
        $shipments = collect($shipments)->map(function ($s) {
            $ts = $s['versanddatum'] ?? $s['datum'] ?? null;
            $d  = is_numeric($ts) ? \Carbon\Carbon::createFromTimestamp((int)$ts) : $this->parseOrderDate($s);
            if ($d) {
                $s['datum_iso']   = $d->toIso8601String();
                $s['datum_human'] = $d->format('Y-m-d');
            }
            return $s;
        })
            // Sort newest shipment first (prefer versanddatum, then datum)
            ->sortByDesc(function ($s) {
                return (int)($s['versanddatum'] ?? $s['datum'] ?? 0);
            })
            ->values()
            ->all();

        $latest = $shipments[0];

        // Flattened, ManyChat-friendly fields
        $orderNumber  = $latest['bestellNr'] ?? ($latest['order_number'] ?? $order_number);
        $orderStatus  = isset($latest['status']) ? (int)$latest['status'] : null;
        $trackingUrl  = $latest['tracking_url'] ?? null;
        $orderDate    = $latest['datum_human'] ?? null;
        $orderDateIso = $latest['datum_iso'] ?? null;

        return response()->json([
            'success'                => true,
            'found'                  => true,
            'message'                => 'Order found',

            // Parent-level fields (map these in ManyChat)
            'order_number'           => $orderNumber,
            'order_status'           => $orderStatus,
            'order_tracking_url'     => $trackingUrl,
            'order_date'             => $orderDate,
            'order_age'              => $orderDate ? Carbon::parse($orderDate)->diffInDays(now()) : null,
            'order_iso'              => $orderDateIso,
            'order_title'            => $resp->json("orderTitle"),
            // Shipments meta (if you want to show/choose between them)
            'shipments_count'        => count($shipments),
            'shipments_json'         => "",
            //json_encode($shipments, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),

            // Keep latest shipment object too (optional)
            'order'                  => $latest,
            // Or the full array:
            'shipments'              => $shipments, // remove if not needed
        ]);
    }



    /** Accepts ISO strings or falls back to last 2 months when both missing */
    protected function resolveDateRange(?string $startDate, ?string $endDate, int $defaultMonths = 2): array
    {
        $start = null;
        $end = null;

        try {
            if ($startDate) $start = Carbon::parse($startDate)->startOfDay();
        } catch (\Throwable $e) {
        }
        try {
            if ($endDate)   $end   = Carbon::parse($endDate)->endOfDay();
        } catch (\Throwable $e) {
        }

        if (!$start && !$end) {
            $start = now()->subMonthsNoOverflow($defaultMonths)->startOfDay();
            $end   = now()->endOfDay();
        } else {
            if (!$start) $start = Carbon::minValue();
            if (!$end)   $end   = now()->endOfDay();
        }

        return [$start, $end];
    }

    /** Robust date parser: supports UNIX seconds in `datum` and common string formats */
    protected function parseOrderDate(array $order): ?Carbon
    {
        foreach (['datum', 'date', 'created_at', 'order_date', 'orderDate', 'ordered_at'] as $key) {
            if (!array_key_exists($key, $order) || $order[$key] === null || $order[$key] === '') continue;

            $raw = $order[$key];

            // Numeric? treat as UNIX timestamp (seconds)
            if (is_numeric($raw)) {
                try {
                    return Carbon::createFromTimestamp((int) $raw);
                } catch (\Throwable $e) {
                    continue;
                }
            }

            // Normalize odd dashes/whitespace
            $clean = preg_replace('/[^\x20-\x7E]/u', '-', (string) $raw);

            // Try common formats first, then generic parse
            foreach (['Y-m-d\TH:i:sP', 'Y-m-d H:i:s', 'Y-m-d', 'd.m.Y H:i:s', 'd.m.Y'] as $fmt) {
                try {
                    return Carbon::createFromFormat($fmt, $clean);
                } catch (\Throwable $e) {
                }
            }
            try {
                return Carbon::parse($clean);
            } catch (\Throwable $e) {
            }
        }

        return null;
    }
}
