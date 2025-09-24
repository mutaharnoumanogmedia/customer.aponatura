@extends('layouts.admin.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">{{ isset($role) ? 'Edit' : 'Create New' }} Role</h2>
        </div>

        <div class="card-body">
            <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
                @csrf
                @isset($role)
                    @method('PUT')
                @endisset

                <div class="mb-4">
                    <label for="role-name" class="form-label fw-bold">Role Name</label>
                    <input type="text" id="role-name" name="name" 
                           value="{{ old('name', $role->name ?? '') }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           required
                           placeholder="Enter role name (e.g. 'content-editor')">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold d-block">Permissions</label>
                    <small class="text-muted d-block mb-2">Select the permissions this role should have</small>
                    
                    <div class="row">
                        @foreach ($permissions->chunk(4) as $chunk)
                            <div class="col-md-3">
                                @foreach ($chunk as $permission)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" 
                                               id="perm-{{ $permission->id }}" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                        @if($permission->description)
                                            <small class="d-block text-muted">{{ $permission->description }}</small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Roles
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ isset($role) ? 'Update' : 'Create' }} Role
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection