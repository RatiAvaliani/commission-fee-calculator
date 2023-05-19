<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    \App\Models\transaction\transactions::init(
        \App\Models\convert_csv::get(file_get_contents(__DIR__ . '/../public/CSV/trans.csv'),
            [
                'date',
                'user_id',
                'user_type',
                'type',
                'amount',
                'currency'
            ]
        )
    );

    return view('welcome');
});
