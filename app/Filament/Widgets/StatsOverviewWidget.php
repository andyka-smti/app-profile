<?php

namespace App\Filament\Widgets;

use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Question;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $publishedPosts = Post::where('status', PostStatus::Published)->count();
        $pendingQuestions = Question::where('status', QuestionStatus::Pending)->count();
        $totalCategories = Category::where('is_active', true)->count();
        $totalPages = Page::count();

        return [
            Stat::make(__('Published Posts'), $publishedPosts)
                ->description(__('Total active articles & news'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make(__('Pending Questions'), $pendingQuestions)
                ->description(__('Requires response from officer'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'gray'),

            Stat::make(__('Active Categories'), $totalCategories)
                ->description(__('Article categorization'))
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),

            Stat::make(__('Static Pages'), $totalPages)
                ->description(__('Profile, vision-mission, etc.'))
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),
        ];
    }
}
