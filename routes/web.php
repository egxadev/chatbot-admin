<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ChatHistoryController;
use App\Http\Controllers\Admin\KnowledgeBaseController;

Route::get('/', function () {
    return inertia('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index')
        ->middleware('permission:permissions.index');

    // roles
    Route::resource('/roles', RoleController::class)
        ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

    // users
    Route::resource('/users', UserController::class)
        ->middleware('permission:users.index|users.create|users.edit|users.delete');

    // knowledge bases
    Route::resource('/knowledge-bases', KnowledgeBaseController::class)
        ->middleware('permission:knowledge-bases.index|knowledge-bases.create|knowledge-bases.edit|knowledge-bases.delete');

    // chat histories
    Route::get('/chat-histories', [ChatHistoryController::class, 'index'])
        ->middleware('permission:chat-histories.index')
        ->name('chat-histories.index');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
