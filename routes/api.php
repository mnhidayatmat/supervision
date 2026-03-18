<?php

use App\Http\Controllers\Api\AiChatController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\TaskApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Tasks API (Kanban + Gantt)
    Route::get('/students/{student}/tasks', [TaskApiController::class, 'index']);
    Route::put('/tasks/{task}/status', [TaskApiController::class, 'updateStatus']);
    Route::post('/tasks/reorder', [TaskApiController::class, 'updateOrder']);
    Route::get('/students/{student}/tasks/gantt', [TaskApiController::class, 'ganttData']);
    Route::put('/tasks/{task}/dates', [TaskApiController::class, 'updateDates']);

    // AI Chat API
    Route::get('/ai/conversations', [AiChatController::class, 'conversations']);
    Route::post('/ai/conversations', [AiChatController::class, 'createConversation']);
    Route::get('/ai/conversations/{conversation}/messages', [AiChatController::class, 'messages']);
    Route::post('/ai/conversations/{conversation}/messages', [AiChatController::class, 'sendMessage']);

    // Notifications API
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationApiController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationApiController::class, 'markAllRead']);
});
