<?php

namespace App\Http\Controllers;

use App\Services\CarsService;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    public function store(Request $request, CarsService $service)
    {
        return $service->insertData($request);
    }
}
