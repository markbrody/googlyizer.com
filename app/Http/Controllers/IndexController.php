<?php

namespace App\Http\Controllers;

use App\EyeType;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index() {
        $eye_types = EyeType::where("is_active", true)->get();
        return view("index", ["eye_types" => $eye_types]);
    }
}
