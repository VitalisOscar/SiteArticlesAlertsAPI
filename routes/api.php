<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('posts/create', [PostsController::class, 'createPost']);

Route::post('subscriptions/new', [SubscriptionController::class, 'subscribeToSite']);
