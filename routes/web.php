<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductInternalController;
use App\Http\Controllers\Admin\PromotionalBannerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShopifyProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\SupportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopifyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class , 'index']);

Route::get('/offline', function () {
    return view('pwa.offline');
});

Auth::routes();


Route::get('/home', [HomeController::class, 'index'])->name('home'); // default route for authenticated admin

Route::get('/shopify/install', [ShopifyController::class, 'redirectToShopify']);
Route::get('/shopify/callback', [ShopifyController::class, 'callback']);

//admin panel routes
Route::prefix("admin")->group(function () {
    Route::middleware("auth")->group(function () {


        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::as("admin.")->group(function () {
            Route::get('profile', [AdminDashboardController::class, 'profile'])->name('profile');
            Route::post("password", [AdminDashboardController::class, 'changePassword'])->name('profile.password');
            Route::put("profile/update", [AdminDashboardController::class, 'changePassword'])->name('profile.update');



            Route::resource('roles', RoleController::class)->middleware("role:Admin");
            Route::resource('users', UserController::class)->middleware("role:Admin");

            Route::resource("brands", BrandController::class);
            Route::resource('promotional-banners', PromotionalBannerController::class);

            Route::resource('support-chat-logs', \App\Http\Controllers\Admin\SupportChatLogController::class)
                ->only(['index', 'show']);
            Route::resource('whatsapp-support-chat-logs', \App\Http\Controllers\Admin\WhatsappSupportChatLogController::class)
                ->only(['index', 'show']);
            Route::resource('click-logs', \App\Http\Controllers\ClickLogController::class)
                ->only(['index']);

            Route::prefix("shopify-products")->group(function () {
                Route::get('index', [ShopifyProductController::class, 'index'])->name('shopify-products.index');
                Route::get('data', [ShopifyProductController::class, 'data'])
                    ->name('shopify-products.data');
                Route::get('show/{product}', [ShopifyProductController::class, 'show'])
                    ->name('shopify-products.show');


                Route::resource('product-internal', ProductInternalController::class);
                Route::get('product-internal/get/data', [ProductInternalController::class, 'data'])
                    ->name('product-internal.data');

                Route::get("sync-form", [ShopifyProductController::class, 'syncForm'])->name('shopify-products.sync-form');
                Route::post('sync', [ShopifyProductController::class, 'sync'])
                    ->name('shopify-products.sync');
            });

            Route::get("orders", [OrderController::class, 'index'])->name('orders.index');
            Route::get("orders/{id}", [OrderController::class, 'show'])->name('orders.show');

            Route::get("customers", [\App\Http\Controllers\Admin\CustomerController::class, 'index'])
                ->name('customers.index');
            Route::get("customer/{id}", [\App\Http\Controllers\Admin\CustomerController::class, 'show'])
                ->name('customer.show');


            Route::post(
                '/brand/{brand}/media/{type}',
                [BrandController::class, 'updateMedia']
            )->name('brand.updateMedia');

            Route::delete('/brand/{brand}/media/{type}', [BrandController::class, 'removeMedia'])
                ->name('brand.removeMedia');
        });
    });
});




//customer panel routes
Route::prefix("customer")
    ->middleware([
        'web', 'detect.brand'
    ])->group(function () {
        Route::get('login', [LoginController::class, 'showCustomerLoginForm'])->name('customer.login.form');
        Route::post('login', [LoginController::class, 'customerLogin'])->name('customer.login.submit');

        Route::post('/magic-link', [\App\Http\Controllers\Auth\MagicLinkController::class, 'send'])
            ->name('magic-link.send')->middleware("throttle:20,60");

        Route::get('/magic-link/verify/{token}', [\App\Http\Controllers\Auth\MagicLinkController::class, 'verify'])
            ->name('magic-link.verify');

        Route::middleware(["web", "auth.customer"])->group(function () {
            Route::post('logout', [LoginController::class, 'customer_logout'])->name('customer.logout');
            Route::get('dashboard', [CustomerDashboardController::class, 'dashboard'])->name('customer.dashboard');
            Route::get('profile', [CustomerDashboardController::class, 'profile'])->name('customer.profile');
            Route::get('profile/edit', [CustomerDashboardController::class, 'editProfile'])
                ->name('customer.profile.edit');
            Route::post('profile/update', [CustomerDashboardController::class, 'updateProfile'])
                ->name('customer.profile.update');
            Route::get('settings', [CustomerDashboardController::class, 'settings'])->name('customer.settings');


            Route::prefix('orders')->group(function () {
                Route::get('/index', [CustomerOrderController::class, 'index'])->name('customer.orders');
                Route::get('show/{order_number}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
                Route::get('invoices', [CustomerOrderController::class, 'invoices'])->name('customer.orders.invoices');
                Route::get('invoice/{invoice_number}', [CustomerOrderController::class, 'invoice'])->name('customer.orders.invoice');
                Route::get('whishlist', [CustomerOrderController::class, 'whishlist'])->name('customer.orders.whishlist');
                //customer.orders.review
                Route::get('review/{order_number}', [CustomerOrderController::class, 'review'])->name('customer.orders.review');
            });
            Route::get('products', [ProductController::class, 'index'])
                ->name('customer.products.index');

            Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
                ->name('customer.notifications.index');
        });

        Route::as("customer.")->group(function () {
            Route::post("click-log", [\App\Http\Controllers\ClickLogController::class, 'store'])
                ->name('click-log.store');
        });
    });

Route::get("api/get-phone-number-verified", [CustomerDashboardController::class, 'getPhoneNumberVerified'])
    ->name('customer.get-phone-number-verified');
Route::get("api/get-customer-orders", [SupportController::class, 'getCustomerOrders'])
    ->name('customer.get-orders');
Route::post("api/store-support-chat-log", [SupportController::class, 'storeSupportChatLog'])
    ->name('customer.store-support-chat-log');
//customer.save-phone-number
Route::post("api/save-phone-number", [CustomerDashboardController::class, 'savePhoneNumber'])
    ->name('customer.save-phone-number');

Route::get('/lang/{locale}', function ($locale) {
    \App\Services\LocaleService::setLocale($locale);
    return redirect()->back();
});




Route::get("test-shopify", function () {
    $shopifyService = new \App\Services\ShopifyGraphQLService();
    $response = $shopifyService->queryProducts();
    return ($response);
});


Route::get("testBroadcasting/{postId}", function ($postId) {
    $post = (new \App\Services\InstagramPostService())->getPostById($postId);
    if (!$post) {
        return "Post not found";
    }
    $mcs = new \App\Services\ManyChatClient();
    $jobs = $mcs->sendInsagramPostToSubscriber('1114999334', $post);
    return $jobs;
});
