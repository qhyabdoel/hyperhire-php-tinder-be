<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\UserLike;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    // 1. List recommended people with pagination
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 10);
        
        // Get people that user hasn't interacted with
        $people = Person::whereDoesntHave('userLikes', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->paginate($perPage);
            
        return response()->json($people);
    }

    // 2. Like a person
    public function like(Request $request, Person $person)
    {
        $user = $request->user();
        
        // Check if already liked/disliked
        $existingLike = UserLike::where('user_id', $user->id)
            ->where('person_id', $person->id)
            ->first();
            
        if ($existingLike) {
            return response()->json(['message' => 'Already interacted with this person'], 400);
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

    // 3. Dislike a person
    public function dislike(Request $request, Person $person)
    {
        $user = $request->user();
        
        // Check if already liked/disliked
        $existingLike = UserLike::where('user_id', $user->id)
            ->where('person_id', $person->id)
            ->first();
            
        if ($existingLike) {
            return response()->json(['message' => 'Already interacted with this person'], 400);
        }
        
        UserLike::create([
            'user_id' => $user->id,
            'person_id' => $person->id,
            'is_liked' => false,
        ]);
        
        return response()->json(['message' => 'Disliked successfully']);
    }

    // 4. Get liked people list
    public function likedPeople(Request $request)
    {
        $user = $request->user();
        $perPage = $request->input('per_page', 10);
        
        $likedPeople = $user->likedPeople()
            ->paginate($perPage);
            
        return response()->json($likedPeople);
    }
}