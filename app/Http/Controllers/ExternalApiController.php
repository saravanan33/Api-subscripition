<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ExternalApiController extends Controller
{
   

    public function testApi() {

       $response = json_decode(File::get(storage_path('country.json')));
        return response()->json($response);
    }
}
