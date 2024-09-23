<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\File;
use Illuminate\Http\Response;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FileController extends Controller
{
    /**
     * Display a listing of the files.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $files = File::all();
            return response()->json(["files" => $files], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(["error" => "Unable to fetch files."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified file.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $file = File::findOrFail($id);
            return response()->json($file, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(["error" => "File not found."], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(["error" => "Unable to fetch file."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created file.
     *
     * @param \App\Http\Requests\FileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request)
    {
        try {
            $imagePath = $request->file('image')->store('images', 'public');

            $file = File::create([
                'path' => $imagePath,
                'ref_id' => $request->ref_id,
                'ref_point' => $request->ref_point,
                'alt_text' => $request->alt_text,
            ]);

            return response()->json($file, Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(["error" => "Unable to create file."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified file.
     *
     * @param \App\Http\Requests\FileRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(FileRequest $request, $id)
    {
        try {
            $file = File::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["error" => "File not found."], Response::HTTP_NOT_FOUND);
        }

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $file->path = $imagePath;
            }

            $file->ref_id = $request->ref_id;
            $file->ref_point = $request->ref_point;
            $file->alt_text = $request->alt_text;
            $file->save();

            return response()->json($file, Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(["error" => "Unable to update file."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified file.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $file = File::findOrFail($id);
            $file->delete();
            return response()->json(["message" => "File deleted successfully."], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(["error" => "File not found."], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(["error" => "Unable to delete file."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
