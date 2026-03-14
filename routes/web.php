<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Roles;
use App\Livewire\Countries;
use App\Livewire\States;
use App\Livewire\Cities;
use App\Livewire\Users;
use App\Livewire\Brands;
use App\Livewire\Products;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/roles', Roles::class)->middleware(['auth'])->name('roles');
Route::get('/countries', Countries::class)->middleware(['auth'])->name('countries');
Route::get('/states', States::class)->middleware(['auth'])->name('states');
Route::get('/cities', Cities::class)->middleware(['auth'])->name('cities');
Route::get('/users', Users::class)->middleware(['auth'])->name('users');
Route::get('/brands', Brands::class)->middleware(['auth'])->name('brands');
Route::get('/products', Products::class)->middleware(['auth'])->name('products');

// Anomaly Control Routes (For Observability Testing)
Route::middleware(['auth'])->group(function () {
    Route::get('/anomalies', function (\App\Services\AnomalyService $anomalyService) {
        return view('anomalies', ['status' => $anomalyService->getStatus()]);
    })->name('anomalies');

    Route::post('/anomalies/toggle', function (Illuminate\Http\Request $request, \App\Services\AnomalyService $anomalyService) {
        if ($request->has('delay')) $anomalyService->setDelay((int) $request->delay);
        if ($request->has('error_rate')) $anomalyService->setErrorRate((int) $request->error_rate);
        if ($request->has('inefficient')) $anomalyService->setInefficientDB((bool) $request->inefficient);
        if ($request->has('reset')) $anomalyService->reset();
        
        return back()->with('message', 'Anomaly status updated!');
    })->name('anomalies.toggle');
});
