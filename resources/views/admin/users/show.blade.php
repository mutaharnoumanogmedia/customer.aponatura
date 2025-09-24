@extends('layouts.admin.app')
@section('content')
    <h1>User: {{ $user->name }}</h1>

    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Roles:</strong> {{ implode(', ', $user->roles->pluck('name')->toArray()) }}</p>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
@endsection
