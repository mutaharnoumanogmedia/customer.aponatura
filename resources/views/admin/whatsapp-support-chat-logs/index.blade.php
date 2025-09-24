@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold mb-0">
                <i class="bi bi-chat-dots me-2"></i>
                Whatsapp Support Chat Logs
            </h1>
        </div>

        <!-- Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover data-table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Whatsapp Support Chat ID</th>
                                <th>Customer Name</th>
                                <th>Whatsapp Number</th>
                                <th>Agent</th>
                                <th>Number of messages</th>
                                <th>Chat Time Stamp</th>
                                <th>Customer Review</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($chatLogs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-medium">{{ $log->whatsapp_session_chat_id }}</td>
                                    <td class="fw-medium">{{ $log->customer_name }}</td>
                                    <td class="fw-medium">{{ $log->whatsapp_number }}</td>
                                    <td class="fw-medium">{{ $log->agent_name }}</td>
                                    <td class="fw-medium">{{ $log->total_messages }}</td>
                                    <td class="fw-medium">
                                        {{ \Carbon\Carbon::parse($log->chat_started_at)->format('Y-m-d H:i:s') }} -
                                        {{ \Carbon\Carbon::parse($log->chat_ended_at)->format('Y-m-d H:i:s') }}</td>
                                    <td class="fw-medium">{!! $log->review_stars != null
                                        ? format_review_stars($log->review_stars) . '<br>' . "<i class='text-muted '>$log->review_note</i>"
                                        : 'No Review' !!}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.whatsapp-support-chat-logs.show', ['whatsapp_support_chat_log' => $log->id]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle me-1"></i>
                                        No chat logs available.
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
@section('script')
    @include('components.data-table-resources')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 25
            });
        });
    </script>
@endsection
