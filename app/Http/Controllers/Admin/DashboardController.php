<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\ShopifyProduct;
use App\Models\PromotionalBanner;
use App\Models\SupportChatLog;
use App\Services\CustomerService;
use App\Services\OrderService;
use App\Traits\CalculateMonthlyRateOfChange;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class DashboardController extends Controller
{
    //
    // use CalculateMonthlyRateOfChange;
    public function index()
    {
        $totalProducts = ShopifyProduct::count();
        $brands = Brand::all();


        // $productStats = $this->CalculateMonthlyRateOfChange(ShopifyProduct::class);
        // $productPercentageChange = $productStats['percent_change'];

        // $allOrders = (new OrderService())->getOrdersFromApi();


        // $allCustomers = (new CustomerService())->getCustomersFromApi();

        $allActiveBanners = PromotionalBanner::where('active', '1')
            ->orderBy('created_at', 'desc')
            ->count();

        $allWhatsappWidgetChatLogs = SupportChatLog::select('customer_email', 'date')->groupBy('customer_email', 'date')->orderByDesc('date')->get();
        $todayWhatsappWidgetChatLogs =  $allWhatsappWidgetChatLogs->filter(function ($log) {
            return Carbon::parse($log->date)->isToday();
        });

        return view('admin.dashboard', compact('totalProducts', 'brands',   'allActiveBanners', 'allWhatsappWidgetChatLogs', 'todayWhatsappWidgetChatLogs'));
    }

    public function getOrdersAPI($skip = 0, $take = 100)
    {
        $orders = (new OrderService())->getOrdersFromApi($skip, $take);
        return response()->json($orders);
    }
    public function getCustomersAPI($skip = 0, $take = 100)
    {
        $customers = (new CustomerService())->getCustomersFromApi($skip, $take);
        return response()->json($customers);
    }


    public function profile()
    {
        return view('admin.profile');
    }



    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'timezone'  => ['required', 'timezone'],
            'avatar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB
            'notifications' => ['nullable', 'array'],
            'notifications.system_emails'   => ['nullable', 'boolean'],
            'notifications.security_emails' => ['nullable', 'boolean'],
            'notifications.order_updates'   => ['nullable', 'boolean'],
            'notifications.marketing_emails' => ['nullable', 'boolean'],
        ]);

        // Handle avatar
        if ($request->hasFile('avatar')) {
            // delete old if any
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        // Normalize boolean notifications
        $notif = $validated['notifications'] ?? [];
        $normalized = [
            'system_emails'   => (bool) ($notif['system_emails']   ?? false),
            'security_emails' => (bool) ($notif['security_emails'] ?? false),
            'order_updates'   => (bool) ($notif['order_updates']   ?? false),
            'marketing_emails' => (bool) ($notif['marketing_emails'] ?? false),
        ];

        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->timezone = $validated['timezone'];
        $user->notifications = $normalized;

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => [
                'name'       => $user->name,
                'email'      => $user->email,
                'timezone'   => $user->timezone,
                'notifications' => $user->notifications,
                'avatar_url' => $user->avatar_path ? Storage::disk('public')->url($user->avatar_path) : null,
            ],
        ]);
    }

    public function changePassword(Request $request)
    {
        // Uses Laravel's built-in current_password rule for the default guard
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $validated = $validator->validated();


        $user = $request->user();
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }
}
