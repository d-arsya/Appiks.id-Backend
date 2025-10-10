<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'docs');
Route::redirect('docs', 'https://appiks.id');
// Route::get('reset', function () {
//     Artisan::call('migrate:fresh-backup');

//     return 'reset success';
// });
// Scramble::registerUiRoute('docs');
// Scramble::registerJsonSpecificationRoute('api.json');
