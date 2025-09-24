@extends('layouts.admin.app')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Click Logs</h2>
        <div class="card">
            <div class="card-header">
                <strong>Event Click Summary</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Event Name</th>
                            <th>Number of Clicks</th>
                            <th>Last Click</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clickLogs as $clickLog)
                            <tr>
                                <td>{{ (str_replace('_', ' ', $clickLog->event_name)) }}</td>
                                <td>{{ $clickLog->total_clicks }}</td>
                                <td>{{ $clickLog->last_clicked_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
