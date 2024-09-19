<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    public function teamAgent(Request $request) : JsonResponse
    {

        return response()->json([], Response::HTTP_OK);
    }
}
