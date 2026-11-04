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
            Stat::make('Artikel Dipublikasi', $publishedPosts)
                ->description('Total artikel & berita aktif')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Pertanyaan Menunggu', $pendingQuestions)
                ->description('Perlu respon dari petugas')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'gray'),

            Stat::make('Kategori Aktif', $totalCategories)
                ->description('Pengelompokan artikel')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),

            Stat::make('Halaman Statis', $totalPages)
                ->description('Profil, visi-misi, dll.')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),
        ];
    }
}
