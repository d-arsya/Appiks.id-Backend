<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('compress:thumbnail')->everyTenSeconds();
Schedule::command('generate:archtype')->hourly();
