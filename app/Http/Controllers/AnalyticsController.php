<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $usersCount = User::count();

        $periodType = $request->get('period_type', 'all');
        $day = (int) $request->get('day', now()->day);
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $salesQuery = Sale::with(['material.materialType', 'material.manufacturer', 'warehouse']);

        switch ($periodType) {
            case 'day':
                $salesQuery->whereDate('sale_date', Carbon::createFromDate($year, $month, $day));
                break;
            case 'month':
                $salesQuery->whereMonth('sale_date', $month)->whereYear('sale_date', $year);
                break;
            case 'year':
                $salesQuery->whereYear('sale_date', $year);
                break;
        }

        $sales = $salesQuery->orderBy('sale_date', 'desc')->get();
        $salesCount = $sales->count();
        $totalQuantity = $sales->sum('quantity');

        return view('analytics', compact(
            'usersCount',
            'sales',
            'salesCount',
            'totalQuantity',
            'periodType',
            'day',
            'month',
            'year'
        ));
    }
}
