<?php

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AspirationController as AdminAspirationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BudgetController as AdminBudgetController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\GalleryImageController;
use App\Http\Controllers\Admin\LostItemController as AdminLostItemController;
use App\Http\Controllers\Admin\OfficialController as AdminOfficialController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\PotentialController as AdminPotentialController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VillageProfileController;
use App\Http\Controllers\Admin\VillageStatisticController;
use App\Http\Controllers\Admin\VillageLocationController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApbdesController;
use App\Http\Controllers\AspirationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PotentialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VillageMapController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/search', [PublicController::class, 'search'])->middleware('throttle:60,1')->name('search');
Route::get('/informasi', [PublicController::class, 'information'])->name('information');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/kategori/{category:slug}', [PostController::class, 'category'])->name('categories.show');

Route::get('/profil', [ProfileController::class, 'profile'])->name('profile');
Route::get('/pemerintahan', [ProfileController::class, 'officials'])->name('officials');

Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/pengumuman/{announcement:slug}', [AnnouncementController::class, 'show'])->name('announcements.show');

Route::get('/agenda', [EventController::class, 'index'])->name('events.index');
Route::get('/peta-desa', [VillageMapController::class, 'index'])->name('village-map.index');

Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/galeri/{gallery:slug}', [GalleryController::class, 'show'])->name('galleries.show');
Route::get('/potensi', [PotentialController::class, 'index'])->name('potentials.index');
Route::get('/potensi/{potential:slug}', [PotentialController::class, 'show'])->name('potentials.show');

Route::get('/barang-hilang', [LostItemController::class, 'index'])->name('lost-items.index');
Route::get('/barang-hilang/{lostItem:slug}', [LostItemController::class, 'show'])->name('lost-items.show');

Route::get('/aspirasi', [AspirationController::class, 'index'])->name('aspirations.index');
Route::post('/aspirasi', [AspirationController::class, 'store'])->middleware('throttle:5,10')->name('aspirations.store');

Route::get('/apbdes', [ApbdesController::class, 'index'])->name('apbdes.index');
Route::get('/apbdes/{year}/unduh', [ApbdesController::class, 'download'])->whereNumber('year')->name('apbdes.download');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Admin dan editor: konten harian
        Route::resource('posts', AdminPostController::class)->except('show');
        Route::patch('posts/{post}/toggle', [AdminPostController::class, 'toggle'])->name('posts.toggle');

        Route::resource('announcements', AdminAnnouncementController::class)->except('show');
        Route::resource('events', AdminEventController::class)->except('show');

        // Ruang Aspirasi: admin dan editor dapat membalas atau menghapus aspirasi yang melanggar ketentuan
        Route::get('aspirations', [AdminAspirationController::class, 'index'])->name('aspirations.index');
        Route::put('aspirations/{aspiration}/reply', [AdminAspirationController::class, 'reply'])->name('aspirations.reply');
        Route::delete('aspirations/{aspiration}', [AdminAspirationController::class, 'destroy'])->name('aspirations.destroy');

        Route::resource('galleries', AdminGalleryController::class)->except('show');
        Route::delete('gallery-images/{image}', [GalleryImageController::class, 'destroy'])->name('gallery-images.destroy');
        Route::patch('galleries/{gallery}/cover/{image}', [GalleryImageController::class, 'cover'])->name('galleries.cover');

        Route::resource('potentials', AdminPotentialController::class)->except('show')
            ->parameters(['potentials' => 'potential']);

        Route::resource('lost-items', AdminLostItemController::class)->except('show')
            ->parameters(['lost-items' => 'lostItem']);
        Route::patch('lost-items/{lostItem}/resolve', [AdminLostItemController::class, 'resolve'])->name('lost-items.resolve');

        // Hanya role admin: struktur, data resmi desa, pengguna, dan pengaturan
        Route::middleware('can:admin-only')->group(function () {
            Route::resource('categories', AdminCategoryController::class)->except(['show', 'create']);

            Route::resource('officials', AdminOfficialController::class)->except('show');
            Route::get('profile', [VillageProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [VillageProfileController::class, 'update'])->name('profile.update');
            Route::resource('statistics', VillageStatisticController::class)->except('show');
            Route::resource('village-locations', VillageLocationController::class)->except('show');

            Route::resource('budgets', AdminBudgetController::class)->except('show');

            Route::resource('users', UserController::class)->except('show');
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        });
    });
});
