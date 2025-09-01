<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('que:work --stop-when-empty')->timezone('Asia/Jakarta')->everySecond();
