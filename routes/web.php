<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/chatbot/flow', [App\Http\Controllers\ChatbotController::class, 'flow']);

// Admin Panel
Route::get('/admin/chatbot', [App\Http\Controllers\AdminChatbotController::class, 'index']);
Route::get('/admin/chatbot/create', [App\Http\Controllers\AdminChatbotController::class, 'create']);
Route::post('/admin/chatbot/store', [App\Http\Controllers\AdminChatbotController::class, 'store']);
Route::get('/admin/chatbot/edit/{key}', [App\Http\Controllers\AdminChatbotController::class, 'edit']);
Route::post('/admin/chatbot/update/{key}', [App\Http\Controllers\AdminChatbotController::class, 'update']);
Route::get('/admin/chatbot/delete/{key}', [App\Http\Controllers\AdminChatbotController::class, 'delete']);
