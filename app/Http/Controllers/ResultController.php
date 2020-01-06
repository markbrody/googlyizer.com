<?php

namespace App\Http\Controllers;

use App\Googlyizer;
use Illuminate\Http\Request;
use Storage;

class ResultController extends Controller
{
    public function show(string $id) {
        if (preg_match("/^[0-9a-f]+$/", $id)) {
            $result = Googlyizer::IMAGE_DIRECTORY . $id;
            if (Storage::exists($result))
                return response(Storage::get($result))
                    ->withHeaders(["Content-Type" => "image/jpeg"]);
            abort(404);
        }
        abort(404);
    }
}
