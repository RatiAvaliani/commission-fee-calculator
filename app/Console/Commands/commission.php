<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class commission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:commission
    {file_name : The last name of the user}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file_name = $this->arguments()['file_name'];

        $fees = \App\Models\transaction\transactions::init(
            \App\Models\convert_csv::get(file_get_contents(__DIR__ . '/../../../public/CSV/' . $file_name),
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

        foreach ($fees as $fee) {
            echo $fee . ', ';
        }
    }
}
