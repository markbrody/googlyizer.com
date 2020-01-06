<?php

namespace App\Http\Controllers;

use App\EyeType;
use App\Googlyizer;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request) {
        $eye_type = EyeType::findOrFail($request->input("eye_type"));
        $image = $request->file("image");
        $googlyizer = new Googlyizer($image, $eye_type);
        if ($googlyizer->detect()) {
            return response()->json(["result" => route("result", $googlyizer->save())]);
        }
    }
}
