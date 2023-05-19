<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;

    private static array $user_list = [];

    public static function set ($id, $user_type): object {
        $user = new user([
            'id' => $id,
            'type' => $user_type,
            'last_transaction_date' => 0
        ]);

        users::$user_list[$id] = $user;
        return $user;
    }

    public static function get ($id): bool|object {
        return isset(users::$user_list[$id]) ? users::$user_list[$id] : false;
    }

    public static function get_last_transaction_date ($id):string {
        return users::$user_list[$id]->last_transaction_date;
    }

    public static function set_last_transaction_date (int $id, string $date):bool {
        return users::$user_list[$id]->last_transaction_date = $date;
    }
}
