<?php

namespace App\Models\transaction;

use App\Models\user\user;
use App\Models\exchange_rates;
use App\Models\commission;
use App\Models\user\users;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;

    public static array $transaction_list = [];

    private static array $fee_amounts = [
        'deposit' => 0.03,
        'withdraw' => [
            'private' => 0.3,
            'business' => 0.5
        ]
    ];

    private static function withdraw (object $user, array $transaction): int|float {
        $fee_percent = transactions::$fee_amounts['withdraw'];
        $fee_amount = 0;

        $in_same_week = commission::in_same_week(
            $transaction['date'],
            users::get_last_transaction_date($transaction['user_id'])
        );

        if ($in_same_week) {
            $user->increase_count();
        } else {
            transactions::$transaction_list[$transaction['user_id']] = [];
            $user->reset_count();
        }

        $above = commission::above_max_amount($transaction['user_id']);

        $fee = $fee_percent[$transaction['user_type']];
        if ($above !== false) {
            $fee_amount = commission::calc($above, $fee);
        }

        users::set_last_transaction_date($transaction['user_id'], $transaction['date']);

        if ($user->get_count() >= 4) {
            $fee_amount = commission::calc($transaction['amount'], $fee);
        }

        if ($transaction['user_type'] === 'business') {
            $fee_amount = commission::calc($transaction['amount'], $fee);
        }
        $fee_amount = round(commission::original_currency($fee_amount, $transaction), 1);

        return $fee_amount;
    }

    private static function deposit (array $transaction): int|float {
        return commission::calc($transaction['amount'], transactions::$fee_amounts['deposit']);
    }

    private static function set(array $transaction): object {
        if (!isset(transactions::$transaction_list[$transaction['user_id']])) {
            transactions::$transaction_list[$transaction['user_id']] = [];
        }

        $trans = new transaction($transaction);
        $in_fee_currency = commission::in_currency($transaction['amount'], $transaction['currency']);

        $trans->excited = $in_fee_currency > commission::$max_amount ? $in_fee_currency - commission::$max_amount : 0;

        if ($transaction['type'] !== 'deposit') {
            transactions::$transaction_list[$transaction['user_id']][] = $trans;
        }

        return $trans;
    }

    private static function init_user (array $transaction): object {
        $user = users::get($transaction['user_id']);
        if (!$user) {
            $user = users::set($transaction['user_id'], $transaction['user_type']);
        }

        return $user;
    }

    private static function make(object $user, array $transaction): int|float {
        if ($transaction['type'] === 'withdraw') {
            $fee_amount = transactions::withdraw($user, $transaction);
        } else {
            $fee_amount = transactions::deposit($transaction);
        }

        return $fee_amount;
    }

    public static function init (array $info, bool $test = false): array {
        $fees = [];

        if (!$test) exchange_rates::get();

        foreach ($info as $transaction) {
            $user = transactions::init_user($transaction);

            $trans_status = transactions::set($transaction)->init();
            $fee_amount = transactions::make($user, $transaction);

            if ($trans_status) $fees[] = round($fee_amount, 2);

            // else error ?...
        }
        return $fees;
    }

}
