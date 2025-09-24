@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>Roles</h1>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                New Role</a>

        </div>
        <div class="card shadow border-0 mt-4">
            <div class="card-body p">
                <table class="table mt-3 data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('components.data-table-resources')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable();
        });
    </script>
@endsection
