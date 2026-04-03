<?php

use App\Http\Controllers\Web\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\adminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\MessageController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\XSSProtection;
use App\Http\Controllers\Web\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'log-request')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'log-request', 'admin', 'xss-protection'])->group(function () {
    Route::get('/admin/index', [adminController::class, 'index'])->name('admin.index');
    // Admin Route to edit a user's roles and permissions
    //Route::get('/admin/edit/{userId}', [adminController::class, 'editUserPermissions'])->name('admin.edit');
    // Admin Route to assign roles and permissions to a user
    Route::post('/admin/assign', [adminController::class, 'assignPermissions'])->name('admin.assign');
    // In your web.php routes file
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});
// Routes for Role CRUD Operations
Route::prefix('messages')->name('messages.')->middleware(['auth', 'can:manage messages'])->group(function () {
    // Display all roles
    Route::get('/', [MessageController::class, 'index'])->name('index');

    // Display form for creating a new role
    Route::get('create', [MessageController::class, 'create'])->name('create');

    // Store a newly created message
    Route::post('store', [MessageController::class, 'store'])->name('store');

    // Display form for editing an existing message
    Route::get('{message}/edit', [MessageController::class, 'edit'])->name('edit');

    // Update the message
    Route::put('{message}', [MessageController::class, 'update'])->name('update');

    // Delete a message
    Route::delete('{message}/{key}', [MessageController::class, 'destroy'])->name('destroy');
});


// Routes for Role CRUD Operations
Route::prefix('roles')->name('roles.')->middleware(['auth', 'can:manage roles', 'xss-protection'])->group(function () {
    // Display all roles
    Route::get('/', [RoleController::class, 'index'])->name('index');

    // Display form for creating a new role
    Route::get('create', [RoleController::class, 'create'])->name('create');

    // Store a newly created role
    Route::post('store', [RoleController::class, 'store'])->name('store');

    // Display form for editing an existing role
    Route::get('{role}/edit', [RoleController::class, 'edit'])->name('edit');

    // Update the role
    Route::put('{role}', [RoleController::class, 'update'])->name('update');

    // Delete a role
    Route::delete('{role}', [RoleController::class, 'destroy'])->name('destroy');
});

// Routes for Permission CRUD Operations
Route::prefix('permissions')->name('permissions.')->middleware(['auth', 'can:manage permissions'])->group(function () {
    // Display all permissions
    Route::get('/', [PermissionController::class, 'index'])->name('index');

    // Display form for creating a new permission
    Route::get('create', [PermissionController::class, 'create'])->name('create');

    // Store a newly created permission
    Route::post('store', [PermissionController::class, 'store'])->name('store');

    // Display form for editing an existing permission
    Route::get('{permission}/edit', [PermissionController::class, 'edit'])->name('edit');

    // Update the permission
    Route::put('{permission}', [PermissionController::class, 'update'])->name('update');

    // Delete a permission
    Route::delete('{permission}', [PermissionController::class, 'destroy'])->name('destroy');
});

Route::get('/payment/select', [PaymentController::class, 'showPaymentForm'])->name('payment.select');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');


require __DIR__ . '/auth.php';
