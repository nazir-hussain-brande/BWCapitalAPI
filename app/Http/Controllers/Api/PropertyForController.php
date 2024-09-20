<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\PropertyFor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyForRequest;
use Symfony\Component\HttpFoundation\Response;

class PropertyForController extends Controller
{
    /**
     * Display a listing of the properties.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $propertiesFor = PropertyFor::all();

            return response()->json(["properties_for" => $propertiesFor], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created property in storage.
     *
     * @param  PropertyForRequest  $request
     * @return JsonResponse
     */
    public function store(PropertyForRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $propertyFor = PropertyFor::create($validated);

            return response()->json(["property_for" => $propertyFor], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified property.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $propertyFor = PropertyFor::find($id);

            if (!$propertyFor) {
                return response()->json(["message" => "PropertyFor not found"], Response::HTTP_NOT_FOUND);
            }

            return response()->json(["property_for" => $propertyFor], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified property in storage.
     *
     * @param  int  $id
     * @param  PropertyForRequest  $request
     * @return JsonResponse
     */
    public function update(int $id, PropertyForRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $propertyFor = PropertyFor::find($id);

            if (!$propertyFor) {
                return response()->json(["message" => "PropertyFor not found"], Response::HTTP_NOT_FOUND);
            }

            $propertyFor->update($validated);

            return response()->json(["property_for" => $propertyFor], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified property from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $propertyFor = PropertyFor::find($id);

            if (!$propertyFor) {
                return response()->json(["message" => "PropertyFor not found"], Response::HTTP_NOT_FOUND);
            }

            $propertyFor->delete();

            return response()->json(["message" => "PropertyFor deleted successfully"], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
