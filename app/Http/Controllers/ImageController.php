<?php

namespace App\Http\Controllers;

use App\Image;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function getAll()
    {
        $userId = Auth::id();
        $user = User::find($userId);

        $images = $user->images()->get();

        return response($images, 200);
    }

    public function getAllByProject($projectId)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        $project = $user->projects()->find($projectId);
        $images = $project->images;

        return response($images, 200);
    }

    public function create(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        $image = new Image;
        $image->name = $request->name;
        $image->description = $request->description;
        $image->url = $request->url;
        $user->images()->save($image);

        $image->projects()->attach($request->projectId);

        return response()->json([
            "message" => "Image created",
            "image" => $image
        ], 201);
    }

    public function delete($id)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if ($user->images()->where('id', $id)->exists()) {
            $image = $user->images()->find($id);
            $image->projects()->detach();
            $image->delete();

            return response()->json([
                "id" => $id,
                "message" => "Image deleted"
            ], 202);
        } else {
            return response()->json([
                "id" => $id,
                "message" => "Image not found"
            ], 404);
        }
    }
}
