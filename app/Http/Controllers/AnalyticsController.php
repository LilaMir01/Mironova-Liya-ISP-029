<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $usersCount = User::count();

        $monthLabelsRu = [
            1 => 'янв.', 2 => 'февр.', 3 => 'мар.', 4 => 'апр.', 5 => 'май', 6 => 'июн.',
            7 => 'июл.', 8 => 'авг.', 9 => 'сент.', 10 => 'окт.', 11 => 'нояб.', 12 => 'дек.',
        ];

        $chartStart = Carbon::create(2026, 3, 1)->startOfMonth();
        $monthsForward = 12;
        $userRegistrationsByMonth = [];
        $maxMonthlyRegistrations = 1;

        for ($i = 0; $i < $monthsForward; $i++) {
            $monthPoint = $chartStart->copy()->addMonths($i);
            $count = User::query()
                ->whereYear('created_at', $monthPoint->year)
                ->whereMonth('created_at', $monthPoint->month)
                ->count();
            $userRegistrationsByMonth[] = [
                'label' => ($monthLabelsRu[$monthPoint->month] ?? '') . ' ' . $monthPoint->year,
                'count' => $count,
            ];
            $maxMonthlyRegistrations = max($maxMonthlyRegistrations, $count);
        }

        return view('analytics', compact(
            'usersCount',
            'userRegistrationsByMonth',
            'maxMonthlyRegistrations',
        ));
    }
}
