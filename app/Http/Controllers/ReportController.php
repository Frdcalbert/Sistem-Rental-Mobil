<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Customer;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter
        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status', 'completed');
        
        // Query data rental
        $query = Rental::with(['customer', 'car'])
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        
        // Filter status
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        
        // Ambil data
        $rentals = $query->orderBy('id', 'desc')->paginate(20);
        
        // Hitung statistik sederhana
        $totalRevenue = $rentals->where('status', 'completed')->sum('total_cost');
        $totalRentals = $rentals->count();
        
        // Data untuk dropdown
        $cars = Car::orderBy('brand')->get();
        $customers = Customer::orderBy('name')->get();
        
        return view('reports.index', compact(
            'rentals',
            'startDate',
            'endDate',
            'status',
            'totalRevenue',
            'totalRentals',
            'cars',
            'customers'
        ));
    }
}