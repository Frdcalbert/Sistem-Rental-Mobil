<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Display listing of customers with sorting options
    public function index(Request $request)
    {
        // Get sorting parameter or default to id_desc
        $sort = $request->get('sort', 'id_desc');
        $search = $request->get('search', '');
        
        // Start query
        $query = Customer::query();
        
        // Apply search if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('driver_license', 'like', '%' . $search . '%');
            });
        }
        
        // Apply sorting
        switch($sort) {
            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'id_desc':
            default:
                $query->orderBy('id', 'desc');
                break;
        }
        
        // Paginate with search and sort parameters
        $customers = $query->paginate(10)
            ->appends([
                'sort' => $sort,
                'search' => $search
            ]);
        
        return view('customers.index', compact('customers', 'sort', 'search'));
    }

    // Show form for creating new customer
    public function create()
    {
        return view('customers.create');
    }

    // Store newly created customer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:200',
            'driver_license' => 'required|string|unique:customers|max:20'
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    // Display specific customer
    public function show(Customer $customer)
    {
        // Load rentals with sorting by latest first
        $customer->load(['rentals' => function($query) {
            $query->orderBy('id', 'desc');
        }]);
        
        return view('customers.show', compact('customer'));
    }

    // Show form for editing customer
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // Update customer in database
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:200',
            'driver_license' => 'required|string|max:20|unique:customers,driver_license,' . $customer->id
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // Delete customer
    public function destroy(Customer $customer)
    {
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}