<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function __construct()
    {
        // only users with customer.read can view index & show
        $this->middleware('permission:customer.read')
            ->only(['index', 'show']);

        // only users with customer.create can see the create form & store
        $this->middleware('permission:customer.create')
            ->only(['create', 'store']);

        // only users with customer.update can edit & update
        $this->middleware('permission:customer.update')
            ->only(['edit', 'update']);

        // only users with customer.delete can destroy
        $this->middleware('permission:customer.delete')
            ->only('destroy');
    }
    public function index(Request $request)
    {
        $skip = $request->input('skip', 0);
        $limit = $request->input('limit', 500);

        // Fetch customers from the API or database
        $customers = []; // Replace with actual data fetching logic
        $customers = (new \App\Services\CustomerService())->getCustomersFromApi($skip, $limit);


        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        // Fetch a single customer by ID from the API or database
        $result = (new \App\Services\CustomerService())->getCustomerById($id);
        $result = $result->getData();
        // converting $result to array
        $result = json_decode(json_encode($result), true);


        if (!$result['success']) {
            return redirect()->route('admin.customers.index')->with('error', 'Customer not found.');
        }

        return view('admin.customers.show', ['customer' => $result['customer']]);
    }
}
