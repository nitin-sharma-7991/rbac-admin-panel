<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

// Role & Permission Routes
Route::middleware('auth:sanctum')->group(function () {
    // Read-only Role APIs (accessible by all authenticated users)
    Route::get('roles', [RoleController::class, 'index']);
    Route::get('roles/{role}', [RoleController::class, 'show']);
    Route::get('roles/{role}/permissions', [RoleController::class, 'getPermissions']);

    // Read-only Permission APIs
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::get('permissions/{permission}', [PermissionController::class, 'show']);

    // Admin-only role & permission mutations
    Route::middleware('role:Admin')->group(function () {
        // Role Management
        Route::post('roles', [RoleController::class, 'store']);
        Route::put('roles/{role}', [RoleController::class, 'update']);
        Route::delete('roles/{role}', [RoleController::class, 'destroy']);
        Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions']);

        // Assign role to user
        Route::post('users/{user}/roles', [RoleController::class, 'assignRoleToUser']);

        // Permission Management
        Route::post('permissions', [PermissionController::class, 'store']);
        Route::put('permissions/{permission}', [PermissionController::class, 'update']);
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy']);
    });
});

// Admin-only route (demo check)
Route::middleware(['auth:sanctum', 'role:Admin'])->get('admin', function () {
    return response()->json(['message' => 'Welcome, Admin!']);
});
