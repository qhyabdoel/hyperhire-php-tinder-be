<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLike;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 1. List recommended users with pagination
    public function index(Request $request)
    {
        // For testing, use user ID 1
        $user = User::find(1);
        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }
        
        $perPage = $request->input('per_page', 10);
        
        // Get users that this user hasn't interacted with (excluding themselves)
        $users = User::whereDoesntHave('userLikes', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('id', '!=', $user->id)
            ->paginate($perPage);
            
        return response()->json($users);
    }

    // 2. Like a user
    public function like(Request $request, User $person)
    {
        // For testing, use user ID 1
        $user = User::find(1);
        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }
        
        // Check if already liked/disliked
        $existingLike = UserLike::where('user_id', $user->id)
            ->where('person_id', $person->id)
            ->first();
            
        if ($existingLike) {
            return response()->json(['message' => 'Already interacted with this user'], 400);
        }
        
        // Create the like
        UserLike::create([
            'user_id' => $user->id,
            'person_id' => $person->id,
            'is_liked' => true,
        ]);
        
        // Increment like count
        $person->increment('like_count');
        
        return response()->json(['message' => 'Liked successfully']);
    }

    // 3. Dislike a user
    public function dislike(Request $request, User $person)
    {
        // For testing, use user ID 1
        $user = User::find(1);
        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }
        
        // Check if already liked/disliked
        $existingLike = UserLike::where('user_id', $user->id)
            ->where('person_id', $person->id)
            ->first();
            
        if ($existingLike) {
            return response()->json(['message' => 'Already interacted with this user'], 400);
        }
        
        UserLike::create([
            'user_id' => $user->id,
            'person_id' => $person->id,
            'is_liked' => false,
        ]);
        
        return response()->json(['message' => 'Disliked successfully']);
    }

    // 4. Get liked users list
    public function likedPeople(Request $request)
    {
        // For testing, use user ID 1
        $user = User::find(1);
        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }
        
        $perPage = $request->input('per_page', 10);
        
        $likedUsers = $user->likedUsers()
            ->paginate($perPage);
            
        return response()->json($likedUsers);
    }
}