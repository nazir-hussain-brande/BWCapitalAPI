<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Property;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyRequest;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $properties = Property::all();

            return response()->json(["properties" => $properties], Response::HTTP_OK);
        }
        catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(["error" => "Something went wrong"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created property in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PropertyRequest $request) : JsonResponse
    {
        try {

            $validated = $request->validated();

            $property = Property::create($validated);

            return response()->json(["property" => $property], Response::HTTP_CREATED);
        }
        catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => "Something went wrong"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        try {
            $property = Property::find($id);

            if (!$property) {
                return response()->json(["message" => "Property not found"], Response::HTTP_NOT_FOUND);
            }
    
            return response()->json(["property" => $property], Response::HTTP_OK);
        }
        catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => "Something went wrong"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified property in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, PropertyRequest $request) : JsonResponse
    {
        try {

            $validated = $request->validated();

            $property = Property::find($id);
            
            if (!$property) {
                return response()->json(["message" => "Property not found"], Response::HTTP_NOT_FOUND);
            }


            $property->update($validated);

            return response()->json(["property" => $property], Response::HTTP_OK);
        }
        catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => "Something went wrong"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified property from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) : JsonResponse
    {
        try {
            $property = Property::find($id);

            if (!$property) {
                return response()->json(["message" => "Property not found"], Response::HTTP_NOT_FOUND);
            }
    
            $property->delete();
    
            return response()->json(["message" => "Property deleted successfully"], Response::HTTP_OK);
        }
        catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => "Something went wrong"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
