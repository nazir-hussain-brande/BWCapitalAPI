<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\PropertyFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyFeatureRequest;
use Symfony\Component\HttpFoundation\Response;

class PropertyFeatureController extends Controller
{
    /**
     * Display a listing of the property features.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $propertyFeatures = PropertyFeature::all();

            return response()->json(["property_features" => $propertyFeatures], Response::HTTP_OK);
        } catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created property feature in storage.
     *
     * @param  PropertyFeatureRequest  $request
     * @return JsonResponse
     */
    public function store(PropertyFeatureRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $propertyFeature = PropertyFeature::create($validated);

            return response()->json(["property_feature" => $propertyFeature], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified property feature.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $propertyFeature = PropertyFeature::find($id);

            if (!$propertyFeature) {
                return response()->json(["message" => "PropertyFeature not found"], Response::HTTP_NOT_FOUND);
            }

            return response()->json(["property_feature" => $propertyFeature], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified property feature in storage.
     *
     * @param  int  $id
     * @param  PropertyFeatureRequest  $request
     * @return JsonResponse
     */
    public function update(int $id, PropertyFeatureRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $propertyFeature = PropertyFeature::find($id);

            if (!$propertyFeature) {
                return response()->json(["message" => "PropertyFeature not found"], Response::HTTP_NOT_FOUND);
            }

            $propertyFeature->update($validated);

            return response()->json(["property_feature" => $propertyFeature], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified property feature from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $propertyFeature = PropertyFeature::find($id);

            if (!$propertyFeature) {
                return response()->json(["message" => "PropertyFeature not found"], Response::HTTP_NOT_FOUND);
            }

            $propertyFeature->delete();

            return response()->json(["message" => "PropertyFeature deleted successfully"], Response::HTTP_OK);
        } catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
