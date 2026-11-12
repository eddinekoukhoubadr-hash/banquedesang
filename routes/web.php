<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Authentification requise
Route::middleware(['auth'])->group(function () {

    // Tableau de bord et statistiques
    Route::get('/dashboard', [BloodBankController::class, 'dashboard'])->name('dashboard');
    Route::get('/bloodbank/export', [BloodBankController::class, 'exportExcel'])->name('bloodbank.export');
    Route::get('/statistics', [App\Http\Controllers\StatisticsController::class, 'index'])->name('statistics.index');

    // PROFIL utilisateur (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mon profil (UserController)
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/user/password', [UserController::class, 'updatePassword'])->name('user.password.update');

    // Banque de sang
    Route::get('/bloodbank/create', [BloodBankController::class, 'create'])->name('bloodbank.create');
    Route::post('/bloodbank', [BloodBankController::class, 'store'])->name('bloodbank.store');
    Route::get('/bloodbank/{id}', [BloodBankController::class, 'show'])->name('bloodbank.show');
    Route::get('/bloodbank/{id}/edit', [BloodBankController::class, 'edit'])->name('bloodbank.edit');
    Route::put('/bloodbank/{id}', [BloodBankController::class, 'update'])->name('bloodbank.update');
    Route::delete('/bloodbank/clear-all', [BloodBankController::class, 'clearAll'])
    ->name('bloodbank.clear')
    ->middleware('auth');

    // Routes admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/bloodbank', [BloodBankController::class, 'index'])->name('bloodbank.index');
        Route::delete('/bloodbank/{id}', [BloodBankController::class, 'destroy'])->name('bloodbank.destroy');
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/service', [App\Http\Controllers\SettingController::class, 'addService'])->name('settings.service.add');
        Route::delete('/settings/service', [App\Http\Controllers\SettingController::class, 'deleteService'])->name('settings.service.delete');
        Route::post('/settings/technicienne', [App\Http\Controllers\SettingController::class, 'addTechnicienne'])->name('settings.tech.add');
        Route::delete('/settings/technicienne', [App\Http\Controllers\SettingController::class, 'deleteTechnicienne'])->name('settings.tech.delete');
    });
});

// Routes Breeze (auth, password, etc.)
require __DIR__ . '/auth.php';

// Blocage de /register
Route::match(['get', 'post'], '/register', function () {
    abort(403, 'Accès interdit. Seul un administrateur peut créer des comptes.');
});
