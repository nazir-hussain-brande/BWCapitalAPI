<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\PropertyType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyTypeRequest;
use Symfony\Component\HttpFoundation\Response;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of the property types.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $propertyTypes = PropertyType::all();

            return response()->json(["property_types" => $propertyTypes], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created property type in storage.
     *
     * @param  PropertyTypeRequest  $request
     * @return JsonResponse
     */
    public function store(PropertyTypeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $propertyType = PropertyType::create($validated);

            return response()->json(["property_type" => $propertyType], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified property type.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $propertyType = PropertyType::find($id);

            if (!$propertyType) {
                return response()->json(["message" => "PropertyType not found"], Response::HTTP_NOT_FOUND);
            }

            return response()->json(["property_type" => $propertyType], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified property type in storage.
     *
     * @param  int  $id
     * @param  PropertyTypeRequest  $request
     * @return JsonResponse
     */
    public function update(int $id, PropertyTypeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $propertyType = PropertyType::find($id);

            if (!$propertyType) {
                return response()->json(["message" => "PropertyType not found"], Response::HTTP_NOT_FOUND);
            }

            $propertyType->update($validated);

            return response()->json(["property_type" => $propertyType], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified property type from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $propertyType = PropertyType::find($id);

            if (!$propertyType) {
                return response()->json(["message" => "PropertyType not found"], Response::HTTP_NOT_FOUND);
            }

            $propertyType->delete();

            return response()->json(["message" => "PropertyType deleted successfully"], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
