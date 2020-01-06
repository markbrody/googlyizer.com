<?php

namespace App\Google;

use App;

class Analytics
{
    public static function show() {
        if (App::environment() != "local")
            return view("google_analytics", [
                "tracking_id" => config("google.tracking_id")
            ]);
    }
}

