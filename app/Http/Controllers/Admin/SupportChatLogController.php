<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportChatLog;
use Illuminate\Http\Request;

class SupportChatLogController extends Controller
{
    public $folder = "admin.support-chat-logs.";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $chatLogs = SupportChatLog::select('customer_email', 'date')->groupBy('customer_email', 'date')->orderByDesc('date')->get();

        return view($this->folder . 'index', compact('chatLogs'));
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
    public function show($customer_email, Request $request)
    {
        //
        
        if ($request->has("date")) {
            $chatLogs = SupportChatLog::where('customer_email', $customer_email)->where('date', $request->input("date"))->get();
        } else {
            $chatLogs = SupportChatLog::where('customer_email', $customer_email)->get();
        }
        return view($this->folder . 'show', compact('chatLogs', 'customer_email'));
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
