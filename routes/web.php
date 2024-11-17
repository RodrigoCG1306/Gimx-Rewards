<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\productController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/sub-agents', [DashboardController::class, 'subAgents'])->name('subagents');
});

Route::prefix('awards')->name('awards.')->group(function() {
    Route::get('/', [AwardController::class, 'index'])->name('index');
    Route::get('/add', [AwardController::class, 'add'])->name('add');
    Route::post('/store', [AwardController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AwardController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [AwardController::class, 'update'])->name('update');
});

Route::prefix('sales')->name('sales.')->group(function () {
    Route::middleware('can:Sales_adding')->get('/', [SalesController::class, 'list'])->name('list');
    Route::middleware('can:Sales_adding')->get('/upload', [SalesController::class, 'upload'])->name('upload');
    Route::middleware('can:Individual_add')->get('/add', [SalesController::class, 'add'])->name('add');
    Route::middleware('can:Individual_add')->post('/store', [SalesController::class, 'store'])->name('store');
    Route::middleware('can:Sales_adding')->post('/import', [SalesController::class, 'import'])->name('import');
    Route::middleware('can:Sales_adding')->post('/correct', [SalesController::class, 'correctSalesData'])->name('correct');
    Route::middleware('can:Sales_managment')->get('/{id}/edit', [SalesController::class, 'edit'])->name('edit');
    Route::middleware('can:Sales_adding')->put('/{id}/update', [SalesController::class, 'update'])->name('update');
    Route::middleware('can:Sales_adding')->get('/export', [SalesController::class, 'export'])->name('download');
});

Route::middleware('can:User_adding')->prefix('agents')->name('agents.')->group(function () {
    Route::get('/', [AgentController::class, 'list'])->name('list');
    Route::get('/add', [AgentController::class, 'add'])->name('add');
    Route::post('/store', [AgentController::class, 'store'])->name('store');
    Route::middleware('can:User_managment')->get('/{id}/edit', [AgentController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [AgentController::class, 'update'])->name('update');
});

Route::middleware('can:Product_managment')->prefix('products')->name('products.')->group(function() {
    Route::get('/', [ProductController::class, 'list'])->name('list');
    Route::get('/add', [ProductController::class, 'add'])->name('add');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ProductController::class, 'update'])->name('update');
});

Route::middleware('can:Company_managment')->prefix('companies')->name('companies.')->group(function() {
    Route::get('/', [CompaniesController::class, 'list'])->name('list');
    Route::get('/add', [CompaniesController::class, 'add'])->name('add');
    Route::post('/store', [CompaniesController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CompaniesController::class, 'edit'])->name('edit');
    Route::get('/{id}/update', [CompaniesController::class, 'update'])->name('update');
});

Route::middleware('auth')->middleware('can:User_managment')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

