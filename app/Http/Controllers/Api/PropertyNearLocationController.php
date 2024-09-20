<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\PropertyNearLocation;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\PropertyNearLocationRequest;

class PropertyNearLocationController extends Controller
{
    /**
     * Display a listing of the near locations.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $locations = PropertyNearLocation::with('property')->get();

            return response()->json(["locations" => $locations], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created near location in storage.
     *
     * @param  PropertyNearLocationRequest  $request
     * @return JsonResponse
     */
    public function store(PropertyNearLocationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $location = PropertyNearLocation::create($validated);

            return response()->json(["location" => $location], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified near location.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $location = PropertyNearLocation::with('property')->find($id);

            if (!$location) {
                return response()->json(["message" => "Location not found"], Response::HTTP_NOT_FOUND);
            }

            return response()->json(["location" => $location], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified near location in storage.
     *
     * @param  int  $id
     * @param  PropertyNearLocationRequest  $request
     * @return JsonResponse
     */
    public function update(int $id, PropertyNearLocationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $location = PropertyNearLocation::find($id);

            if (!$location) {
                return response()->json(["message" => "Location not found"], Response::HTTP_NOT_FOUND);
            }

            $location->update($validated);

            return response()->json(["location" => $location], Response::HTTP_OK);
        } catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified near location from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $location = PropertyNearLocation::find($id);

            if (!$location) {
                return response()->json(["message" => "Location not found"], Response::HTTP_NOT_FOUND);
            }

            $location->delete();

            return response()->json(["message" => "Location deleted successfully"], Response::HTTP_OK);
        } catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
