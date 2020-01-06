<?php

namespace App;

class Adsense
{
    public static function show(string $ad_unit) {
        return view("google_adsense", [
            "data_ad_client" => config("adsense.client"),
            "data_ad_slot" => config("adsense.ad_units.$ad_unit.ad_slot"),
        ]);
    }

}
