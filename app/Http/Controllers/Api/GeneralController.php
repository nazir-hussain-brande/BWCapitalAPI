<?php

namespace App\Http\Controllers\Api;

use App\Models\Team;
use App\Models\PropertyFor;
use Illuminate\Http\Request;
use App\Models\PropertyType;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    public function teamAgent(Request $request) : JsonResponse
    {
        $status = $request->get("status", 1);
        $agent = $request->get("agent", 1);
    
        $teams = Team::where('status', $status)
                     ->where('agent', $agent)
                     ->get();
    
        return response()->json(["teams" => $teams], Response::HTTP_OK);
    }

    public function propertyTypes(Request $request) : JsonResponse
    {
        $status = $request->get("status", 1);

        $propertyTypes = PropertyType::where('status', $status)->get();
    
        return response()->json(["propertyTypes" => $propertyTypes], Response::HTTP_OK);
    }

    public function propertyFor(Request $request) : JsonResponse
    {
        $status = $request->get("status", 1);

        $propertyFor = PropertyFor::where('status', $status)->get();
    
        return response()->json(["propertyFor" => $propertyFor], Response::HTTP_OK);
    }
}
