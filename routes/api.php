<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\QuestionController;
use App\Http\Controllers\Api\V1\QuestionTopicController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\SlideController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes for CMS & Layanan Masyarakat (V1)
|--------------------------------------------------------------------------
|
| Endpoint REST API publik untuk dikonsumsi oleh aplikasi klien (Flutter).
|
*/

Route::prefix('v1')->group(function () {
    // Artikel / Berita
    Route::get('/posts', [PostController::class, 'index'])->name('api.v1.posts.index');
    Route::get('/posts/{slug}', [PostController::class, 'show'])->name('api.v1.posts.show');

    // Halaman Statis
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('api.v1.pages.show');

    // Kategori
    Route::get('/categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('api.v1.categories.show');

    // Slide Banner
    Route::get('/slides', [SlideController::class, 'index'])->name('api.v1.slides.index');

    // Menu Navigasi (header, footer, sidebar)
    Route::get('/menus/{location}', [MenuController::class, 'show'])->name('api.v1.menus.show');

    // Pengaturan Situs (Profil Instansi, Kontak, Medsos)
    Route::get('/settings', [SettingController::class, 'index'])->name('api.v1.settings.index');

    // Layanan Masyarakat: Topik & FAQ
    Route::get('/question-topics', [QuestionTopicController::class, 'index'])->name('api.v1.question-topics.index');
    Route::get('/faqs', [FaqController::class, 'index'])->name('api.v1.faqs.index');

    // Layanan Masyarakat: Pertanyaan Publik
    Route::get('/questions', [QuestionController::class, 'index'])->name('api.v1.questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('api.v1.questions.store');

    // Media & Storage with full CORS headers for Web & Mobile clients
    Route::match(['get', 'head', 'options'], '/storage/{path}', function (Request $request, string $path) {
        if ($request->isMethod('options')) {
            return response('', 204, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
                'Access-Control-Allow-Headers' => '*',
            ]);
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            abort(404);
        }

        $filePath = $disk->path($path);
        $mimeType = $disk->mimeType($path) ?? 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    })->where('path', '.*')->name('api.v1.storage');
});
