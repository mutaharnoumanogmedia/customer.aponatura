@extends('layouts.admin.app')
@section('content')
    <div class="container py-4">
        <h3 class="mb-4">Customer Details</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th scope="row">ID</th>
                            <td>{{ $customer['id'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Name</th>
                            <td>{{ $customer['first_name'] }} {{ $customer['last_name'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td>{{ $customer['email'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Address</th>
                            <td>
                                {{ $customer['address'] }}<br>
                                {{ $customer['address2'] }}<br>
                                {{ $customer['postal_code'] }} {{ $customer['city'] }}<br>
                                {{ $customer['country'] }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td>
                                @if ($customer['status'] == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Vendor ID</th>
                            <td>{{ $customer['vendor_id'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Zendesk User ID</th>
                            <td>{{ $customer['zendesk_user_id'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Billomat Client ID</th>
                            <td>{{ $customer['billomat_client_id'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">ActiveCampaign Contact ID</th>
                            <td>{{ $customer['ac_contact_id'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary mt-4">← Back to Customer List</a>
    </div>
@endsection
