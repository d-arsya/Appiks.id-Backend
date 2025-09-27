<?php

use App\Http\Controllers\TagController;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'docs');
Route::get('coba', [TagController::class, 'coba']);
Route::get('reset', function () {
    Artisan::call('migrate:fresh --seed');

    return 'reset success';
});
Scramble::registerUiRoute('docs');
Scramble::registerJsonSpecificationRoute('api.json');
