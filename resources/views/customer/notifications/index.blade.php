@extends('layouts.customer.app')
@section('content')
    <div class="container py-4">
        <h2>
            {{ __('Notifications') }}
        </h2>

        @if (sizeof($notifications) > 0)
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Notification Type') }}</th>
                        <th>{{ __('Message') }}</th>
                        <th>{{ __('Created At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $index => $notification)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucfirst($notification['type']) }}</td>
                            <td>{{ $notification['message'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($notification['created_at'])->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                {{ __('No notifications found.') }}
            </div>
        @endif
    </div>
@endsection
