@extends('layouts.admin.app')
@section('content')
    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 fw-bold">
                    <i class="bi bi-chat-dots me-2"></i>
                    Support Chat Logs for {{ $chatLog->whatsapp_session_chat_id }}

                </h1>
                <div class="mb-3">
                    <small class="text-muted">on {{ $chatLog->created_at->format('Y-m-d H:i:s') }}</small>
                    <div>Agent: {{ $chatLog->agent_name }}</div>
                    <div>Customer: {{ $chatLog->customer_name }} , {{ $chatLog->whatsapp_number }}</div>
                </div>
            </div>

            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <!-- Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Sender</th>
                                <th scope="col">Message</th>
                                <th scope="col">Timestamp</th>


                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $message)
                                <tr>
                                    <td class="fw-medium">
                                        {{ rand(1, 2) == 1 ? $chatLog->customer_name : $chatLog->agent_name }}</td>
                                    <td style="max-width: 400px; white-space: pre-wrap; word-wrap: break-word;">
                                        {{ $message }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($message->date ?? now()->subDays(rand(0, 30))->subMinutes(rand(0, 1440)))->format('Y-m-d H:i:s') }}
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle me-1"></i>
                                        No chat logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
