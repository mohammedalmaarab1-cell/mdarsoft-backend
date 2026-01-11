<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\VolunteerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;

// Public Routes
Route::get('/search', [SearchController::class, 'search']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/team-members', [TeamMemberController::class, 'index']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news}', [NewsController::class, 'show']);
Route::post('/messages', [MessageController::class, 'store']);
Route::post('/volunteers', [VolunteerController::class, 'store']);
Route::get('/settings', [SettingController::class, 'index']);
Route::post('/chat', [ChatController::class, 'chat']);

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Admin Stats
    Route::get('/admin/stats', [DashboardController::class, 'stats']);

    // Admin CRUD Resources
    Route::apiResource('admin/projects', ProjectController::class);
    Route::apiResource('admin/news', NewsController::class);
    Route::apiResource('admin/services', ServiceController::class);
    Route::apiResource('admin/team-members', TeamMemberController::class);
    Route::apiResource('admin/users', UserController::class);
    Route::post('/admin/settings/bulk', [SettingController::class, 'updateBulk']);
    Route::post('/admin/upload', [UploadController::class, 'upload']);
    
    // Admin Management
    Route::get('/admin/messages', [MessageController::class, 'index']);
    Route::delete('/admin/messages/{message}', [MessageController::class, 'destroy']);
    
    Route::get('/admin/volunteers', [VolunteerController::class, 'index']);
    Route::put('/admin/volunteers/{volunteer}', [VolunteerController::class, 'update']);
    Route::delete('/admin/volunteers/{volunteer}', [VolunteerController::class, 'destroy']);

    // Activity Logs
    Route::get('/admin/logs', function() {
        return ActivityLogResource::collection(ActivityLog::with('user')->latest()->get());
    });
});
