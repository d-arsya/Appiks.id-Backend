<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'docs');

Scramble::registerUiRoute('docs');
Scramble::registerJsonSpecificationRoute('api.json');
