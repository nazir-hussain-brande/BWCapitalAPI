<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\File;
use App\Models\Team;
use App\Models\Property;
use App\Models\PropertyFor;
use App\Models\PropertyType;
use Illuminate\Http\Response;
use App\Models\PropertyFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PropertyNearLocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyRequest;
use App\Http\Requests\PropertyImageUploadRequest;

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
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created property in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function store(PropertyRequest $request): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validated();

            $agentId = Team::where('title_en', $validated['agent_id']['title_en'])->first();
            if (!$agentId) {
                $agentId = Team::create([
                    'title_en' => $validated['agent_id']['title_en'],
                    'title_ar' => $validated['agent_id']['title_ar'],
                ])->id;
            } else {
                $agentId = $agentId->id;
            }

            $propertyTypeId = PropertyType::where('title_en', $validated['property_type']['title_en'])->first();
            if (!$propertyTypeId) {
                $propertyTypeId = PropertyType::create([
                    'title_en' => $validated['property_type']['title_en'],
                    'title_ar' => $validated['property_type']['title_ar'],
                ])->id;
            } else {
                $propertyTypeId = $propertyTypeId->id;
            }

            $propertyForId = PropertyFor::where('title_en', $validated['property_for']['title_en'])->first();
            if (!$propertyForId) {
                $propertyForId = PropertyFor::create([
                    'title_en' => $validated['property_for']['title_en'],
                    'title_ar' => $validated['property_for']['title_ar'],
                ])->id;
            } else {
                $propertyForId = $propertyForId->id;
            }

            $propertyData = array_merge($validated, [
                'agent_id'      => $agentId,
                'property_type' => $propertyTypeId,
                'property_for'  => $propertyForId,
            ]);

            $property = Property::create($propertyData);

            $propertyFeatureIds = [];
            $propertyFeaturesData = [];

            foreach ($validated['property_features'] as $feature) {
                $propertyFeature = PropertyFeature::where('title_en', $feature['title_en'])->first();
                
                if (!$propertyFeature) {
                    $propertyFeaturesData[] = [
                        'title_en' => $feature['title_en'],
                        'title_ar' => $feature['title_ar'],
                        'description_en' => $feature['description_en'],
                        'description_ar' => $feature['description_ar'],
                        'status' => $feature['status'],
                        'created_at' => now()
                    ];
                } else {
                    $propertyFeatureIds[] = $propertyFeature->id;
                }
            }

            if (!empty($propertyFeaturesData)) {
                $insertedIds = PropertyFeature::insert($propertyFeaturesData);

                $propertyFeatureIds = array_merge($propertyFeatureIds, $insertedIds);
            }

            $property->propertyFeatures()->attach($propertyFeatureIds);

            $locations = [];
            foreach ($validated['property_near_location'] as $location) {
                $locations[] = [
                    'location_en' => $location['location_en'],
                    'location_ar' => $location['location_ar'],
                    'distance' => $location['distance'],
                    'property_id' => $property->id,
                    'created_at' => now(),
                ];
            }

            PropertyNearLocation::insert($locations);

            DB::commit();

            return response()->json(["property" => $property], Response::HTTP_CREATED);
        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Upload the related images for property.
     *
     * @param  \Illuminate\Http\PropertyImageUploadRequest  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(PropertyImageUploadRequest $request) : JsonResponse
    {
        try {
            
            Log::info($request->all());

            $validated = $request->validated();
    
            $path = $request->file('image')->store('property_images/' . $request->input("ref_id"), "public");
    
            $file = File::updateOrCreate(
                [
                    'ref_id' => $validated['ref_id'],
                    'ref_point' => $validated['ref_point'],
                ],
                [
                    'name' => basename($path),
                    'path' => $path,
                    'alt_text' => $validated['alt_text'],
                ]
            );
    
            return response()->json(['message' => 'Image uploaded successfully', 'file' => $file]);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
