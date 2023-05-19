<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class convert_csv extends Model
{
    use HasFactory;

    public static function get (string $list, array $names):array {
        $info_list = explode("\n", trim($list));
        $export_list = [];

        for ($i=0; $i < count($info_list); $i++){
            $export_list[$i] = [];
            $item_list = explode(',', trim($info_list[$i]));

            for ($b=0; $b < count($names); $b++){
                $export_list[$i][$names[$b]] = trim($item_list[$b]);
            }
        }

        return $export_list;
    }
}
