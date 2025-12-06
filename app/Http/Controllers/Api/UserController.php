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
    public function like(Request $request)
    {
        $request->validate([
            'liker_id' => 'required|exists:users,id',
            'liked_id' => 'required|exists:users,id|different:liker_id',
        ]);
        
        $user = User::find($request->liker_id);
        $person = User::find($request->liked_id);
        
        if (!$user || !$person) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Check if already liked/disliked
        $existingLike = UserLike::where('user_id', $user->id)
            ->where('person_id', $person->id)
            ->first();
            
        if ($existingLike) {
            // If already liked, return success
            if ($existingLike->is_liked) {
                return response()->json(['message' => 'Already liked this user']);
            }
            // If disliked, update to liked
            $existingLike->update(['is_liked' => true]);
            $person->increment('like_count');
            return response()->json(['message' => 'Changed from dislike to like']);
        }
        
        // Create new like
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
    public function dislike(Request $request)
    {
        $request->validate([
            'disliker_id' => 'required|exists:users,id',
            'disliked_id' => 'required|exists:users,id|different:disliker_id',
        ]);
        
        $user = User::find($request->disliker_id);
        $person = User::find($request->disliked_id);
        
        if (!$user || !$person) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Check if already liked/disliked
        $existingLike = UserLike::where('user_id', $user->id)
            ->where('person_id', $person->id)
            ->first();
            
        if ($existingLike) {
            // If already disliked, return success
            if (!$existingLike->is_liked) {
                return response()->json(['message' => 'Already disliked this user']);
            }
            // If liked, update to dislike
            $existingLike->update(['is_liked' => false]);
            $person->decrement('like_count');
            return response()->json(['message' => 'Changed from like to dislike']);
        }
        
        // Create new dislike
        UserLike::create([
            'user_id' => $user->id,
            'person_id' => $person->id,
            'is_liked' => false,
        ]);
        
        return response()->json(['message' => 'Disliked successfully']);
    }

    // 4. Get liked users list
    public function likedPeople(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $perPage = $request->input('per_page', 10);
        
        $likedUsers = $user->likedUsers()
            ->paginate($perPage);
            
        return response()->json($likedUsers);
    }
}