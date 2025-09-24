@extends('layouts.admin.app')
@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 fw-bold">
            <i class="bi bi-chat-dots me-2"></i>
            Support Chat Logs for {{ $customer_email }}
            @if(isset($_GET['date']))
                <small class="text-muted">on {{ $_GET['date'] }}</small>
            @endif
        </h1>
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
                            @if (!isset($_GET['date']))
                                <th scope="col">Date</th>
                            @endif
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chatLogs as $log)
                            <tr>
                                <td class="fw-medium">{{ $log->sender }}</td>
                                <td style="max-width: 400px; white-space: pre-wrap; word-wrap: break-word;">
                                    {{ $log->message }}
                                </td>
                                @if (!isset($_GET['date']))
                                    <td>{{ $log->date }}</td>
                                @endif
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ isset($_GET['date']) ? 3 : 4 }}" class="text-center text-muted py-4">
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
