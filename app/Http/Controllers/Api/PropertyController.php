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
use App\Models\PropertyFeatureAll;
use Illuminate\Support\Facades\Log;
use App\Models\PropertyNearLocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyRequest;
use Illuminate\Support\Facades\Storage;
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
            $properties = Property::with([
                "files" => function ($q) {
                    return $q->whereNot("ref_point", "news_main_image")
                                ->whereNot("ref_point", "blogs_main_image");
                }
            ])->get();

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
     * @param  \Illuminate\Http\PropertyRequest  $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function store(PropertyRequest $request): JsonResponse
    {
        DB::beginTransaction();
        
        try {

            $validated = $request->validated();

            $slug_en = strtolower(str_replace(' ', '_', $validated['title_en']));
            $slug_ar = strtolower(str_replace(' ', '_', $validated['title_ar']));

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

                $slug_en = strtolower(str_replace(' ', '_', $validated['property_type']['title_en']));
                $slug_ar = strtolower(str_replace(' ', '_', $validated['property_type']['title_ar']));

                $propertyTypeId = PropertyType::create([
                    'title_en' => $validated['property_type']['title_en'],
                    'title_ar' => $validated['property_type']['title_ar'],
                    'slug_en' => $slug_en,
                    'slug_ar' => $slug_ar,
                    'status' => 1
                ])->id;
            } else {
                $propertyTypeId = $propertyTypeId->id;
            }

            $propertyForId = PropertyFor::where('title_en', $validated['property_for']['title_en'])->first();
            if (!$propertyForId) {

                $slug_en = strtolower(str_replace(' ', '_', $validated['property_for']['title_en']));
                $slug_ar = strtolower(str_replace(' ', '_', $validated['property_for']['title_ar']));

                $propertyForId = PropertyFor::create([
                    'title_en' => $validated['property_for']['title_en'],
                    'title_ar' => $validated['property_for']['title_ar'],
                    'slug_en' => $slug_en,
                    'slug_ar' => $slug_ar,
                    'status' => 1
                ])->id;
            } else {
                $propertyForId = $propertyForId->id;
            }

            $propertyData = array_merge($validated, [
                'slug_en'       => $slug_en,
                'slug_ar'       => $slug_ar,
                'agent_id'      => $agentId,
                'property_type' => $propertyTypeId,
                'property_for'  => $propertyForId
            ]);

            $property = Property::create($propertyData);

            $mainImages =  [
                [
                    "name"      => basename($validated["property_main_image"]),
                    "path"      => $validated["property_main_image"],
                    "ref_id"    => $property->id,
                    "ref_point" => "property_main_image",
                    "from_api"  => 1,
                    "created_at" => now()
                ],
                [
                    "name"      => basename($validated["property_broucher"]),
                    "path"      => $validated["property_broucher"],
                    "ref_id"    => $property->id,
                    "ref_point" => "property_broucher",
                    "from_api"  => 1,
                    "created_at" => now()
                ]
            ];            

            $mainGallery = [];
            foreach ($validated["property_main_gallery"] as $link) {
                $mainGallery[] = [
                    "name"      => basename($link),
                    "path"      => $link,
                    "ref_id"    => $property->id,
                    "ref_point" => "property_main_gallery",
                    "from_api"  => 1,
                    "created_at" => now()
                ];
            }

            $files = array_merge($mainImages, $mainGallery);
            File::insert($files);
            

            // Handle property features
            $propertyFeatureIds = [];
            foreach ($validated['property_features'] as $feature) {
                $propertyFeature = PropertyFeature::where('title_en', $feature['title_en'])->first();   
                if (!$propertyFeature) {
                    $propertyFeature = PropertyFeature::create([
                        'title_en' => $feature['title_en'],
                        'title_ar' => $feature['title_ar'],
                        'description_en' => $feature['description_en'],
                        'description_ar' => $feature['description_ar'],
                        'status' => $feature['status']
                    ]);
                    $insertedIds = [$propertyFeature->id];
                } else {
                    $insertedIds = [$propertyFeature->id];
                }
                $propertyFeatureIds = array_merge($propertyFeatureIds, $insertedIds);
            }
            $property->propertyFeatures()->attach($propertyFeatureIds);

            // Handle property near locations
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
            $property = Property::whereId($id)->with([
                "files" => function ($q) {
                    return $q->whereNot("ref_point", "news_main_image")
                                ->whereNot("ref_point", "blogs_main_image");
                }
            ])->first();

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
     * @param  \Illuminate\Http\PropertyRequest  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\JsonResponse
    */
    public function update(int $id, PropertyRequest $request): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validated();

            $slug_en = strtolower(str_replace(' ', '_', $validated['title_en']));
            $slug_ar = strtolower(str_replace(' ', '_', $validated['title_ar']));
    
            $property = Property::findOrFail($id);
    
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

                $slug_en = strtolower(str_replace(' ', '_', $validated['property_type']['title_en']));
                $slug_ar = strtolower(str_replace(' ', '_', $validated['property_type']['title_ar']));

                $propertyTypeId = PropertyType::create([
                    'title_en' => $validated['property_type']['title_en'],
                    'title_ar' => $validated['property_type']['title_ar'],
                    'slug_en' => $slug_en,
                    'slug_ar' => $slug_ar,
                    'status' => 1
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
                'agent_id' => $agentId,
                'property_type' => $propertyTypeId,
                'property_for' => $propertyForId,
                'slug_en' => $slug_en,
                'slug_ar' => $slug_ar,
            ]);
    
            $property->update($propertyData);
    
            // Handle property features
            $propertyFeatureIds = [];

            foreach ($validated['property_features'] as $feature) {
                $propertyFeature = PropertyFeature::where('title_en', $feature['title_en'])->first();
                if (!$propertyFeature) {
                    $propertyFeature = PropertyFeature::create([
                        'title_en' => $feature['title_en'],
                        'title_ar' => $feature['title_ar'],
                        'description_en' => $feature['description_en'],
                        'description_ar' => $feature['description_ar'],
                        'status' => $feature['status']
                    ]);
                    $insertedIds = [$propertyFeature->id];
                } else {
                    $insertedIds = [$propertyFeature->id];
                }

                $propertyFeatureIds = array_merge($propertyFeatureIds, $insertedIds);
            }

            $property->propertyFeatures()->sync($propertyFeatureIds);
    
            // Handle property near locations
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
    
            PropertyNearLocation::where('property_id', $property->id)->delete();
            PropertyNearLocation::insert($locations);
    
            DB::commit();
            return response()->json(["property" => $property], Response::HTTP_OK);
        } catch (Exception $e) {

            DB::rollBack();
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
        DB::beginTransaction();
    
        try {
            $property = Property::find($id);
    
            if (!$property) {
                return response()->json(["message" => "Property not found"], Response::HTTP_NOT_FOUND);
            }
    
            PropertyFeatureAll::where('property_id', $property->id)->update(['property_id' => 0]);
            PropertyNearLocation::where('property_id', $property->id)->update(['property_id' => 0]);
    
            $property->delete();
    
            DB::commit();
            
            return response()->json(["message" => "Property deleted successfully"], Response::HTTP_OK);
        }
        catch (Exception $e) {
            
            DB::rollBack();
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

            $file = File::where("ref_id", $request->input("ref_id"))
                        ->where("ref_point", $request->input("ref_point"))
                        ->first();

            if ($file) {
                Storage::disk('public')->delete($file->path);
            }

            $path = "property_images/". $request->input("ref_id"). "/" .$request->input("ref_point");
            $imgPath = $request->file('image')->store($path, "public");

            $validated = $request->validated();
            $file = File::updateOrCreate(
                [
                    'ref_id' => $validated['ref_id'],
                    'ref_point' => $validated['ref_point'],
                ],
                [
                    'name' => basename($imgPath),
                    'path' => $imgPath,
                    'alt_text' => $validated['alt_text'],
                ]
            );

            Log::info($file->toArray());
    
            return response()->json(['message' => 'Image uploaded successfully', 'file' => $file]);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
