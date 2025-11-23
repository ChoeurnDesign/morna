<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\ProductController as AdminProductController; 
use App\Http\Controllers\Admin\ProcessController as AdminProcessController;
use App\Http\Controllers\Admin\OriginController as AdminOriginController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\TeamMemberController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// --- FRONTEND ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/product', [ProductController::class, 'showMain'])->name('product.show');
Route::get('/process', [ProcessController::class, 'index'])->name('process');
Route::get('/origin', [OriginController::class, 'index'])->name('origin');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// --- ADMIN ROUTES ---
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('hero-slides', HeroSlideController::class)->except(['show']);

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
        Route::delete('/settings/logo', [SettingsController::class, 'deleteLogo'])->name('settings.logo.delete');
        Route::delete('/settings/favicon', [SettingsController::class, 'deleteFavicon'])->name('settings.favicon.delete');

        Route::prefix('about')->name('about.')->group(function () {
            Route::get('/', [AboutController::class, 'index'])->name('index');
            Route::post('/update', [AboutController::class, 'updateAbout'])->name('update');
            Route::delete('/banner', [AboutController::class, 'deleteBanner'])->name('banner.delete');
        });

        // Admin products
        Route::resource('products', AdminProductController::class);
        Route::post('/products/{product}/set-main', [AdminProductController::class, 'setAsMain'])->name('products.set-main');
        Route::post('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');

        Route::resource('process-steps', AdminProcessController::class)->except(['show']);

        Route::resource('origins', AdminOriginController::class)->except(['show']);

        Route::resource('faqs', AdminFaqController::class)->except(['show']);
        Route::post('/faqs/reorder', [AdminFaqController::class, 'reorder'])->name('faqs.reorder');
        Route::post('/faqs/{faq}/toggle-status', [AdminFaqController::class, 'toggleStatus'])->name('faqs.toggle-status');

        Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
        Route::post('/contacts/{contact}/mark-read', [AdminContactController::class, 'markAsRead'])->name('contacts.mark-read');
        Route::post('/contacts/{contact}/mark-unread', [AdminContactController::class, 'markAsUnread'])->name('contacts.mark-unread');
    });

// --- AUTH/PROFILE ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';