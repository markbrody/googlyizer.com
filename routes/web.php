<?php

Route::get("/", "IndexController@index")->name("index");
Route::get("result/{id}", "ResultController@show")->name("result");
Route::post("upload", "UploadController@store")->name("upload");
