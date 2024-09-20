<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TeamRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    /**
     * Display a listing of the team members.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $teams = Team::all();

            return response()->json(["teams" => $teams], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created team member in storage.
     *
     * @param  TeamRequest  $request
     * @return JsonResponse
     */
    public function store(TeamRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $team = Team::create($validated);

            return response()->json(["team" => $team], Response::HTTP_CREATED);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified team member.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $team = Team::find($id);

            if (!$team) {
                return response()->json(["message" => "Team member not found"], Response::HTTP_NOT_FOUND);
            }

            return response()->json(["team" => $team], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified team member in storage.
     *
     * @param  int  $id
     * @param  TeamRequest  $request
     * @return JsonResponse
     */
    public function update(int $id, TeamRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $team = Team::find($id);

            if (!$team) {
                return response()->json(["message" => "Team member not found"], Response::HTTP_NOT_FOUND);
            }

            $team->update($validated);

            return response()->json(["team" => $team], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified team member from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $team = Team::find($id);

            if (!$team) {
                return response()->json(["message" => "Team member not found"], Response::HTTP_NOT_FOUND);
            }

            $team->delete();

            return response()->json(["message" => "Team member deleted successfully"], Response::HTTP_OK);
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return response()->json(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
