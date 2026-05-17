<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SizeResource;
use App\Models\Size;

class SizeController extends Controller
{
    public function index()
    {
        return SizeResource::collection(Size::all());
    }
}
