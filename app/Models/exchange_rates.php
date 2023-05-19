<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class exchange_rates extends Model
{
    use HasFactory;

    public static $rates = [
        'EUR' => [
            'USD' => 1.1497,
            'JPY' => 129.53
        ],
    ];

    public static function get () {
        $response = Http::get('https://developers.paysera.com/tasks/api/currency-exchange-rates');
        self::$rates['EUR'] = $response->json()['rates'];
    }
}
