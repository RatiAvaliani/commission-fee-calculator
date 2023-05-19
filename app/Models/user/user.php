<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{

    private string $type;
    private int $count = 0;
    public string $last_transaction_date;

    public function increase_count () {

        $this->count = $this->count + 1;
    }
    public function reset_count () {
        $this->count = 0;
    }

    public function __construct(array $attr = [])
    {
        $this->type = $attr['type'];
        $this->last_transaction_date = $attr['last_transaction_date'];
    }

    public function get_count () {
        return $this->count;
    }
}
