<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Car;
use App\Models\Rental;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalCustomers = Customer::count();
        $totalCars = Car::count();
        $availableCars = Car::where('status', 'available')->count();
        $rentedCars = Car::where('status', 'rented')->count();
        $maintenanceCars = Car::where('status', 'maintenance')->count();
        
        // Rental statistics
        $activeRentals = Rental::where('status', 'active')->count();
        $completedRentals = Rental::where('status', 'completed')->count();
        $cancelledRentals = Rental::where('status', 'cancelled')->count();
        
        // Today's rentals
        $today = Carbon::today();
        $todayStartRentals = Rental::whereDate('start_date', $today)->count();
        $todayEndRentals = Rental::whereDate('end_date', $today)->count();
        
        // Recent rentals (sorted by ID descending)
        $recentRentals = Rental::with(['customer', 'car'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
        
        // Recent customers (sorted by ID descending)
        $recentCustomers = Customer::orderBy('id', 'desc')
            ->take(5)
            ->get();
        
        // Recent cars (sorted by ID descending)
        $recentCars = Car::orderBy('id', 'desc')
            ->take(5)
            ->get();
        
        // Monthly revenue (example)
        $currentMonth = Carbon::now()->month;
        $monthlyRevenue = Rental::whereMonth('created_at', $currentMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_cost');
        
        $stats = [
            'total_customers' => $totalCustomers,
            'total_cars' => $totalCars,
            'available_cars' => $availableCars,
            'rented_cars' => $rentedCars,
            'maintenance_cars' => $maintenanceCars,
            'active_rentals' => $activeRentals,
            'completed_rentals' => $completedRentals,
            'cancelled_rentals' => $cancelledRentals,
            'today_start_rentals' => $todayStartRentals,
            'today_end_rentals' => $todayEndRentals,
            'monthly_revenue' => $monthlyRevenue,
            'recent_rentals' => $recentRentals,
            'recent_customers' => $recentCustomers,
            'recent_cars' => $recentCars,
        ];
        
        return view('dashboard', compact('stats'));
    }
}