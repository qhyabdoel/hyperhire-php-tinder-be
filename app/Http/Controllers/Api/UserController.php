<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *     title="Tinder-like API",
 *     version="1.0.0",
 *     description="API for Tinder-like application"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class UserController extends Controller
{
    // 1. List recommended users with pagination
    /**
     * @OA\Get(
     *     path="/api/users/recommended",
     *     summary="Get recommended users to like/dislike",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of recommended users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        try {
            // Get all users with pagination
            $users = User::paginate($perPage);
                
            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['error' => 'Server error occurred'], 500);
        }
}

    // 2. Like a user
    /**
     * @OA\Post(
     *     path="/api/likes",
     *     summary="Like a user",
     *     tags={"Likes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"liker_id", "liked_id"},
     *             @OA\Property(property="liker_id", type="integer", example=1),
     *             @OA\Property(property="liked_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully liked the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Liked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or cannot like yourself"
     *     )
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/api/dislikes",
     *     summary="Dislike a user",
     *     tags={"Likes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"disliker_id", "disliked_id"},
     *             @OA\Property(property="disliker_id", type="integer", example=1),
     *             @OA\Property(property="disliked_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully disliked the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Disliked successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or cannot dislike yourself"
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/api/users/{id}/likes",
     *     summary="Get users liked by a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of users liked by the specified user",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
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