<?php

namespace App\Models;

use App\Models\exchange_rates;
use App\Models\transaction\transactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commission extends Model
{

    use HasFactory;

    public static int $max_amount = 1000;
    private static string $currency = 'EUR';

    public static function calc (int|float $amount, int|float $percentage): int|float {
        return ($percentage / 100) * $amount;
    }

    public static function in_currency (int|float $amount, string $currency): int|float {
        if ($currency !== commission::$currency) {
            $rate = exchange_rates::$rates[commission::$currency][$currency];
            $amount = $amount / $rate;
        }

        return $amount;
    }

    public static function original_currency (int|float $amount, array $transaction): int|float {
        if ($transaction['currency'] !== commission::$currency) {
            $rate = exchange_rates::$rates[commission::$currency][$transaction['currency']];
            $amount = $amount * $rate;
        }

        return $amount;
    }

    public static function above_max_amount (int $user_id): int|float|bool {
        $total_amount = 0;
        $transactions = transactions::$transaction_list[$user_id];

        for ($i=0; $i < count($transactions); $i++) {
            $last_trans_id = $i-1 > -1 ? $i-1 : false;

            if ($transactions[$i]->currency !== commission::$currency) {
                $currency_rate = exchange_rates::$rates[commission::$currency][$transactions[$i]->currency];
                $total_amount += ($transactions[$i]->amount / $currency_rate);
            } else {
                $total_amount += $transactions[$i]->amount;
            }

            if ($last_trans_id !== false && isset($transactions[$last_trans_id]) && $transactions[$last_trans_id]->excited !== 0) {
                $total_amount = $total_amount - $transactions[$last_trans_id]->excited;
            }

            if ($i === count($transactions)-1) {
                if ($total_amount > commission::$max_amount) {
                    $transactions[$i]->excited = $total_amount - commission::$max_amount;
                }
            }
        }

        if ($total_amount > commission::$max_amount) {
            return $total_amount - commission::$max_amount;
        }

        return false;
    }

    public static function in_same_week (string $current_date, string $old_date): bool {
        if ($old_date == 0) return true;

        $current_date = new \DateTime($current_date);
        $old_date = new \DateTime($old_date);
        $diff = date_diff($old_date, $current_date)->days;

        return $current_date->format("W") === $old_date->format("W") && $diff <= 7;
    }

}
