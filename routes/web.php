<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Shariya\PdfGenerator\Facades\PdfGenerator;

Route::get('/', function () {
    return view('welcome');
});

Route::get('clear', function () {
    Cache::flush();
    return "success";
});
Route::get('pdf-test', function () {
    $data = [
        'title' => "shariya",
        "desc" => "descrripijafsdf"
    ];
    PdfGenerator::generateFromView('test', $data, 'test.pdf');
});
