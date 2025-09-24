@extends('layouts.admin.app')
@section('content')
    <h1>Role: {{ $role->name }}</h1>
    <p><strong>Permissions:</strong></p>
    <ul>
        @foreach ($role->permissions as $perm)
            <li>{{ $perm->name }}</li>
        @endforeach
    </ul>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back to Roles</a>
@endsection
