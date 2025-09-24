<?php

namespace App\Http\Controllers;

use App\Models\ClickLog;
use Illuminate\Http\Request;

class ClickLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //
        $clickLogs = ClickLog::select('event_name', \DB::raw('COUNT(*) as total_clicks'), \DB::raw('MAX(created_at) as last_clicked_at'))
            ->groupBy('event_name')
            ->orderBy('total_clicks', 'desc')
            ->get();

        return view('admin.click-logs.index', compact('clickLogs'));
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
        $existingLog = ClickLog::where('event_name', $request->input('event_name'))
            ->where('user_email', auth()->guard('customer')->user()->email ?? null)
            ->where('user_ip', $request->ip())
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if ($existingLog) {
            $clickLog = $existingLog;
        } else {
            $clickLog = ClickLog::create([
                'event_name' => $request->input('event_name'),
                'user_email' => auth()->guard('customer')->user()->email ?? null,
                'user_ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'additional_info' => $request->input('additional_info'),
            ]);
        }

        return response()->json($clickLog, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClickLog $clickLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClickLog $clickLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClickLog $clickLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClickLog $clickLog)
    {
        //
    }
}
