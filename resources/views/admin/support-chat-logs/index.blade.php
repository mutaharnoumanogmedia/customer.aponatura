@extends('layouts.admin.app')
@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">
            <i class="bi bi-chat-dots me-2"></i>
            Widget Support Chat Logs
        </h1>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th scope="col">Customer Email</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chatLogs as $log)
                            <tr>
                                <td class="fw-medium">{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $log->customer_email }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->date)->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.support-chat-logs.show', ['support_chat_log' => $log->customer_email, 'date' => $log->date]) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
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
