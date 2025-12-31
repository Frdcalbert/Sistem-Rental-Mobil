<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Customer;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    // Display listing of rentals with sorting options
    public function index(Request $request)
    {
        // Get sorting parameter or default to id_desc
        $sort = $request->get('sort', 'id_desc');
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        // Start query with relationships
        $query = Rental::with(['customer', 'car']);
        
        // Apply search if provided
        if (!empty($search)) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            })->orWhereHas('car', function($q) use ($search) {
                $q->where('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('license_plate', 'like', '%' . $search . '%');
            });
        }
        
        // Apply status filter if provided
        if (!empty($status) && in_array($status, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }
        
        // Apply sorting
        switch($sort) {
            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'customer_asc':
                $query->join('customers', 'rentals.customer_id', '=', 'customers.id')
                      ->orderBy('customers.name', 'asc');
                break;
            case 'customer_desc':
                $query->join('customers', 'rentals.customer_id', '=', 'customers.id')
                      ->orderBy('customers.name', 'desc');
                break;
            case 'start_date_asc':
                $query->orderBy('start_date', 'asc');
                break;
            case 'start_date_desc':
                $query->orderBy('start_date', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('total_cost', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('total_cost', 'desc');
                break;
            case 'days_asc':
                $query->orderBy('total_days', 'asc');
                break;
            case 'days_desc':
                $query->orderBy('total_days', 'desc');
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
        
        // Paginate with parameters
        $rentals = $query->paginate(10)
            ->appends([
                'sort' => $sort,
                'search' => $search,
                'status' => $status
            ]);
        
        return view('rentals.index', compact('rentals', 'sort', 'search', 'status'));
    }

    // Show form for creating new rental
    public function create()
    {
        $customers = Customer::orderBy('name', 'asc')->get();
        $cars = Car::where('status', 'available')
            ->orderBy('brand', 'asc')
            ->orderBy('model', 'asc')
            ->get();
        return view('rentals.create', compact('customers', 'cars'));
    }

    // Store newly created rental
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Calculate total days and cost
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate);
        
        $car = Car::find($validated['car_id']);
        $totalCost = $totalDays * $car->daily_rate;

        // Create rental
        $rental = Rental::create([
            'customer_id' => $validated['customer_id'],
            'car_id' => $validated['car_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'total_cost' => $totalCost,
            'status' => 'active'
        ]);

        // Update car status
        $car->update(['status' => 'rented']);

        return redirect()->route('rentals.index')
            ->with('success', 'Rental berhasil ditambahkan! Total biaya: Rp ' . number_format($totalCost, 0, ',', '.'));
    }

    // Display specific rental
    public function show(Rental $rental)
    {
        $rental->load(['customer', 'car']);
        return view('rentals.show', compact('rental'));
    }

    // Show form for editing rental
    public function edit(Rental $rental)
    {
        $customers = Customer::orderBy('name', 'asc')->get();
        $cars = Car::orderBy('brand', 'asc')->orderBy('model', 'asc')->get();
        return view('rentals.edit', compact('rental', 'customers', 'cars'));
    }

    // Update rental in database
    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,completed,cancelled'
        ]);

        // Recalculate if dates changed
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate);
        
        $car = Car::find($validated['car_id']);
        $totalCost = $totalDays * $car->daily_rate;

        $validated['total_days'] = $totalDays;
        $validated['total_cost'] = $totalCost;

        // Update car status if rental status changed
        $oldCar = $rental->car;
        
        if ($validated['status'] == 'completed' || $validated['status'] == 'cancelled') {
            $car->update(['status' => 'available']);
        } elseif ($validated['status'] == 'active' && $oldCar->id != $car->id) {
            // Jika mobil berubah dan status aktif, update status mobil lama dan baru
            $oldCar->update(['status' => 'available']);
            $car->update(['status' => 'rented']);
        }

        $rental->update($validated);

        return redirect()->route('rentals.index')
            ->with('success', 'Data rental berhasil diperbarui!');
    }

    // Delete rental
    public function destroy(Rental $rental)
    {
        // Free the car when rental is deleted
        $car = $rental->car;
        $car->update(['status' => 'available']);
        
        $rental->delete();
        
        return redirect()->route('rentals.index')
            ->with('success', 'Rental berhasil dihapus!');
    }
}