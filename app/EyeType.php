<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EyeType extends Model
{
    protected $fillable = ["name", "is_active", ];

    public function getLabelAttribute() {
        $parts = explode(" ", strtolower($this->name));
        $last_word = array_pop($parts);
        array_unshift($parts, $last_word);
        return implode("-", $parts);
    }

    public function getFilenameAttribute() {
        return "images/labels/" . $this->label . ".png";
    }

}
