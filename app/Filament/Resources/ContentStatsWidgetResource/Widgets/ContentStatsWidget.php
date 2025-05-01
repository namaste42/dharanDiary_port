<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ContentStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            // Stat::make('Total Articles', Article::count()),
            // Stat::make('Articles Today', Article::whereDate('created_at', today())->count()),
            // Stat::make('Pending Articles', Article::where('status', 'pending')->count()),
            Stat::make('Articles This Week', Article::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()),
            Stat::make('Rejected Articles', Article::where('status', 'rejected')->count()),
            Stat::make('Draft Articles', Article::where('status', 'draft')->count()),
        ];
    }
}

class UserStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Users Registered Today', User::whereDate('created_at', today())->count()),
        ];
    }
}

class SystemHealthWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Failed Jobs', DB::table('failed_jobs')->count()),
            Stat::make('Queue Size', DB::table('jobs')->count()),
            Stat::make('Disk Usage', number_format((disk_total_space("/") - disk_free_space("/")) / 1e+9, 2) . ' GB Used'),
        ];
    }
}

class MostActiveAuthors extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return User::withCount('articles')->orderByDesc('articles_count')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('articles_count')->label('Articles'),
        ];
    }
}

class MostViewedArticles extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Article::orderByDesc('views')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->limit(50),
            TextColumn::make('views'),
        ];
    }
}

class TopCategoriesWidget extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Category::withCount('articles')->orderByDesc('articles_count')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('articles_count')->label('Articles'),
        ];
    }
}

// class TopTagsWidget extends TableWidget
// {
//     protected function getTableQuery(): Builder
//     {
//         return \App\Models\Tag::withCount('articles')->orderByDesc('articles_count')->limit(5);
//     }

//     protected function getTableColumns(): array
//     {
//         return [
//             TextColumn::make('name'),
//             TextColumn::make('articles_count')->label('Articles'),
//         ];
//     }
// }

class MostCommentedArticles extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Article::withCount('comments')->orderByDesc('comments_count')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->limit(50),
            TextColumn::make('comments_count')->label('Comments'),
        ];
    }
}

class UpcomingScheduledArticles extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Article::where('created_at', '>', now())->orderBy('created_at')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->limit(50),
            TextColumn::make('created_at')->dateTime(),
        ];
    }
}

class ArticlesMissingImages extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Article::whereNull('image')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->limit(50),
        ];
    }
}

class ArticlesWithoutSEO extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        return Article::whereNull('meta_keywords')->orWhereNull('meta_description')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->limit(50),
        ];
    }
}

class AverageTimeToPublish extends BaseWidget
{
    protected function getCards(): array
    {
        $avg = Article::whereNotNull('published_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, published_at)) as avg_seconds'))
            ->value('avg_seconds');

        $hours = round($avg / 3600, 2);

        return [
            Stat::make('Avg Time to Publish', $hours . ' hrs'),
        ];
    }
}
