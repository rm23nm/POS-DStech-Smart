<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function generateSimpleCode($prefix, $tableName, $columnName, $padLength = 3)
    {
        $lastRecord = \Illuminate\Support\Facades\DB::table($tableName)
            ->where('RecordOwnerID', \Illuminate\Support\Facades\Auth::user()->RecordOwnerID)
            ->where($columnName, 'like', $prefix . '%')
            // To ensure numerical sorting, we might need a raw query, but standard string desc is okay if padded
            ->orderBy($columnName, 'desc')
            ->first();

        if ($lastRecord) {
            $lastCode = intval(substr($lastRecord->{$columnName}, strlen($prefix)));
            return $prefix . str_pad($lastCode + 1, $padLength, '0', STR_PAD_LEFT);
        } else {
            return $prefix . str_pad(1, $padLength, '0', STR_PAD_LEFT);
        }
    }
}