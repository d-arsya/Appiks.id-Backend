<?php

use App\Http\Controllers\DocsController;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::get('/changelog', [DocsController::class, 'changelog']);


Route::redirect('/', 'docs');

Scramble::registerUiRoute('docs');
Scramble::registerJsonSpecificationRoute('api.json');
