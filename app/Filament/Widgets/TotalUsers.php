<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalUsers extends BaseWidget
{
    protected function getStats(): array
    {
        $users = User::select('created_at')->get();

        $totalUsers = $users->count();
        $usersLast30Days = $users->whereBetween('created_at', [now()->subDays(30), now()])->count();

        return [
            Stat::make('Total users', $totalUsers)
                ->description("{$usersLast30Days} new user(s) in the last 30 days")
        ];
    }
}
