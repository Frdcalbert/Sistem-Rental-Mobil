<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    // Display listing of cars with sorting options
    public function index(Request $request)
    {
        // Get sorting parameter or default to id_desc
        $sort = $request->get('sort', 'id_desc');
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        // Start query
        $query = Car::query();
        
        // Apply search if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('license_plate', 'like', '%' . $search . '%')
                  ->orWhere('color', 'like', '%' . $search . '%');
            });
        }
        
        // Apply status filter if provided
        if (!empty($status) && in_array($status, ['available', 'rented', 'maintenance'])) {
            $query->where('status', $status);
        }
        
        // Apply sorting
        switch($sort) {
            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'brand_asc':
                $query->orderBy('brand', 'asc')->orderBy('model', 'asc');
                break;
            case 'brand_desc':
                $query->orderBy('brand', 'desc')->orderBy('model', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('daily_rate', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('daily_rate', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('year', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
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
        $cars = $query->paginate(10)
            ->appends([
                'sort' => $sort,
                'search' => $search,
                'status' => $status
            ]);
        
        return view('cars.index', compact('cars', 'sort', 'search', 'status'));
    }

    // Show form for creating new car
    public function create()
    {
        return view('cars.create');
    }

    // Store newly created car
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'license_plate' => 'required|string|unique:cars|max:15',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'daily_rate' => 'required|numeric|min:100000',
            'color' => 'required|string|max:30'
        ]);

        $validated['status'] = 'available';
        
        Car::create($validated);

        return redirect()->route('cars.index')
            ->with('success', 'Mobil berhasil ditambahkan!');
    }

    // Display specific car
    public function show(Car $car)
    {
        // Load rentals with sorting by latest first
        $car->load(['rentals' => function($query) {
            $query->orderBy('id', 'desc');
        }]);
        
        return view('cars.show', compact('car'));
    }

    // Show form for editing car
    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    // Update car in database
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'license_plate' => 'required|string|max:15|unique:cars,license_plate,' . $car->id,
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'daily_rate' => 'required|numeric|min:100000',
            'status' => 'required|in:available,rented,maintenance',
            'color' => 'required|string|max:30'
        ]);

        $car->update($validated);

        return redirect()->route('cars.index')
            ->with('success', 'Data mobil berhasil diperbarui!');
    }

    // Delete car
    public function destroy(Car $car)
    {
        $car->delete();
        
        return redirect()->route('cars.index')
            ->with('success', 'Mobil berhasil dihapus!');
    }
}