@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1> Manage Users</h1>

            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                New User</a>
        </div>
        <div class="card shadow border-0 mt-4">
            <div class="card-body ">
                <div class="table-responisve ">
                    <table class="table mt-3 data-table">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
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
