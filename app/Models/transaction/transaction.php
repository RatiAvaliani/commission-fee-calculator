<?php

namespace App\Models\transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    public $amount;
    public $currency;
    public $excited;
    protected $type;

    public function __construct(array $attr) {
        $this->type = $attr['type'];
        $this->amount = $attr['amount'];
        $this->currency = $attr['currency'];
    }

    public function init (): int|float {
        // Transaction Api Call?...
        return true;
    }
}
