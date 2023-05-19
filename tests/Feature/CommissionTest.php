<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommissionTest extends TestCase
{
    public $test_starter_transactions_csv = '2014-12-31,4,private,withdraw,1200.00,EUR
            2015-01-01,4,private,withdraw,1000.00,EUR
            2016-01-05,4,private,withdraw,1000.00,EUR
            2016-01-05,1,private,deposit,200.00,EUR
            2016-01-06,2,business,withdraw,300.00,EUR
            2016-01-06,1,private,withdraw,30000,JPY
            2016-01-07,1,private,withdraw,1000.00,EUR
            2016-01-07,1,private,withdraw,100.00,USD
            2016-01-10,1,private,withdraw,100.00,EUR
            2016-01-10,2,business,deposit,10000.00,EUR
            2016-01-10,3,private,withdraw,1000.00,EUR
            2016-02-15,1,private,withdraw,300.00,EUR
            2016-02-19,5,private,withdraw,3000000,JPY';
    public $test_starter_transactions_csv_answers = [
        0.6, 3.0, 0.0, 0.06, 1.50, 0.0, 0.7, 0.3, 0.3, 3.0, 0.0, 0.0, 8611.4
    ];

    public function test_starter_transactions(): void
    {
        $fees = \App\Models\transaction\transactions::init(
            \App\Models\convert_csv::get($this->test_starter_transactions_csv,
                [
                    'date',
                    'user_id',
                    'user_type',
                    'type',
                    'amount',
                    'currency'
                ]
            ), true
        );

        for ($i = 1; $i < count($this->test_starter_transactions_csv_answers); $i++) {
            if ($fees[$i] != $this->test_starter_transactions_csv_answers[$i]) {
                $this->assertTrue(false, 'Test Failed');
            }
        }

        $this->assertTrue(true);
    }
}
