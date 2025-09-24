<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Notifications\PortalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $guard = $this->getActiveGuard() === 'customer' ? 'customer' : 'admin';
        $notifications =
            Notification::where('notifiable_id', auth()->guard($guard)->user()->id)

            ->where('notifiable_type', ($guard))

            //if guard customer, then use brand where clause
            ->when($guard === 'customer', function ($query, $guard) {
                $brandName = app('currentBrand')->name;

                $query->where('brand', $brandName);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view($guard . '.notifications.index', [
            'notifications' => $notifications,
            'guard' => $guard,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $notification = new Notification();
        $notification->notifiable_id = $request->input('notifiable_id');
        $notification->type = $request->input('type', 'customer');
        $notification->title = $request->input('title', 'New Notification');
        $notification->link = $request->input('link', '');
        $notification->data = $request->input('data', 'No additional data provided');
        $notification->read_at = null; // Set to null for unread notifications
        $notification->created_at = now();
        $notification->updated_at = now();


        $notification->save();

        return response()->json($notification, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
