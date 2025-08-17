<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BusinessConnection
{
    public static function getConnectionByCode(string $businessCode): \Illuminate\Database\Connection
    {
        $connections = config('business_connections');

        if (!isset($connections[$businessCode])) {
            throw new \Exception("Invalid business code: {$businessCode}");
        }

        return DB::connection($connections[$businessCode]);
    }

    public static function getConnectionName(string $businessCode): string
    {
        $connections = config('business_connections');

        if (!isset($connections[$businessCode])) {
            throw new \Exception("Invalid business code: {$businessCode}");
        }

        return $connections[$businessCode];
    }
}
