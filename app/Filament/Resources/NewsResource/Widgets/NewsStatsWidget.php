<?php

namespace App\Filament\Resources\NewsResource\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;

class NewsStatsWidget extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Total Articles', \App\Models\Article::count())
                ->description('All time total')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Published Today', \App\Models\Article::whereDate('created_at', today())->count())
                ->description('Fresh news')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('success'),

            Stat::make('Pending Approval', \App\Models\Article::where('status', 'pending')->count())
                ->description('Needs review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
