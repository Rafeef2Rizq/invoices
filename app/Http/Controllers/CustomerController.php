<?php
// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = auth()->user()
            ->customers()
            ->latest()
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request)
    {
        auth()->user()->customers()->create($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        $this->authorizeOwnership($customer);

        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $this->authorizeOwnership($customer);

        $customer->update($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorizeOwnership($customer);

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted.');
    }

    private function authorizeOwnership(Customer $customer): void
    {
        abort_if($customer->user_id !== auth()->id(), 403);
    }
}