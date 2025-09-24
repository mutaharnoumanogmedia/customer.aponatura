@extends('layouts.admin.app')
@section('content')
    <h1>{{ isset($user) ? 'Edit' : 'New' }} User</h1>

    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
        @csrf
        @isset($user)
            @method('PUT')
        @endisset

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password <i>default: baaboo123</i></label>
            <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}
                value="{{ old('password', isset($user) ? '' : 'baaboo123') }}">

            {{-- Show hint only if user is being edited --}}
            {{-- If creating a new user, we assume the default password is baaboo123 --}}
            @if (isset($user))
                <small class="form-text text-muted">Leave blank to keep current password.</small>
            @endif
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}value="{{ old('password_confirmation', isset($user) ? '' : 'baaboo123') }}">
        </div>

        <div class="mb-3">
            <label>Roles</label><br>
            @foreach ($roles as $role)
                <label class="me-2">
                    <input type="checkbox" name="roles[]" value="{{ $role }}"
                        {{ isset($userRoles) && in_array($role, $userRoles) ? 'checked' : '' }}>
                    {{ $role }}
                </label>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
