<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function getAll()
    {
        $userId = Auth::id();
        $user = User::find($userId);

        $projects = $user->projects()->get();

        return response($projects, 200);
    }

    public function create(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        $project = new Project;
        $project->name = $request->name;
        $project->description = $request->description;

        $user->projects()->save($project);

        return response()->json([
            "message" => "Project created",
            "project" => $project
        ], 201);
    }

    public function get($id)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if ($user->projects()->where('id', $id)->exists()) {
            $project = $user->projects()->where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($project, 200);
        } else {
            return response()->json([
                "message" => "Project not found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if ($user->projects()->where('id', $id)->exists()) {
            $project = $user->projects()->find($id);
            $project->name = is_null($request->name) ? $project->name : $request->name;
            $project->description = is_null($request->description) ? $project->description : $request->description;

            $user->projects()->save($project);

            return response()->json([
                "message" => "Project updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Project not found"
            ], 404);
        }
    }

    public function delete($id)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if ($user->projects()->where('id', $id)->exists()) {
            $project = $user->projects()->find($id);
            $project->delete();

            return response()->json([
                "id" => $id,
                "message" => "Project deleted"
            ], 202);
        } else {
            return response()->json([
                "id" => $id,
                "message" => "Project not found"
            ], 404);
        }
    }
}
