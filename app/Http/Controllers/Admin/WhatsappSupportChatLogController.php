<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappSupportChatLog;
use Illuminate\Http\Request;

class WhatsappSupportChatLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $chatLogs = WhatsappSupportChatLog::orderByDesc('created_at')
            ->get();

        return view("admin.whatsapp-support-chat-logs.index", compact('chatLogs'));
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $chatLog = WhatsappSupportChatLog::findOrFail($id);
        $messages = json_decode($chatLog->chat_log, true);
        return view("admin.whatsapp-support-chat-logs.show", compact('chatLog', 'messages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
