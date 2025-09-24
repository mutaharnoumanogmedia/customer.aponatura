<?php

namespace App\Http\Controllers;

use App\Models\ManyChatSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManyChatSubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $query = ManyChatSubscriber::query();

        if (request()->has('first_name')) {
            $query->where('first_name', 'like', '%' . request()->input('first_name') . '%');
        }
        if (request()->has('last_name')) {
            $query->where('last_name', 'like', '%' . request()->input('last_name') . '%');
        }
        if (request()->has('phone')) {
            $query->where('phone', 'like', '%' . request()->input('phone') . '%');
        }
        if (request()->has('tag')) {
            $query->where('tag', 'like', '%' . request()->input('tag') . '%');
        }

        $subscribers = $query->get();

        return response()->json($subscribers);
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
        //
        $validation = Validator::make($request->all(), [
            'subscriber_id' => 'required|string|unique:many_chat_subscribers,subscriber_id,except,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'tag' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 200);
        }
        $data = $validation->validated();
        $data['subscribed_at'] = $request->has('subscribed_at') ? $request->input('subscribed_at') : now();

        $manyChatSubscriber = ManyChatSubscriber::updateOrCreate(
            ['subscriber_id' => $data['subscriber_id']],
            $data
        );

        return response()->json(['message' => 'Subscriber created successfully.', 'subscriber' => $manyChatSubscriber], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ManyChatSubscriber $manyChatSubscriber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ManyChatSubscriber $manyChatSubscriber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManyChatSubscriber $manyChatSubscriber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManyChatSubscriber $manyChatSubscriber)
    {
        //
    }
}
