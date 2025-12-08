# Tinder-like App Backend

## Database Schema

### Tables

#### users
- `id` - Primary Key
- `name` - string
- `email` - string (unique)
- `email_verified_at` - timestamp, nullable
- `password` - string
- `age` - unsigned integer, nullable
- `pictures` - JSON, nullable (stores array of picture URLs)
- `location` - JSON, nullable (stores location data)
- `like_count` - unsigned integer, default: 0 (tracks number of likes given by user)
- `notified` - boolean, default: false (tracks if user has been notified about excessive likes)
- `remember_token` - string, nullable
- `created_at` - timestamp
- `updated_at` - timestamp

#### user_likes
- `id` - Primary Key
- `user_id` - Foreign Key to users.id (cascading delete)
- `person_id` - Foreign Key to users.id (cascading delete)
- `is_liked` - boolean, default: true
- `created_at` - timestamp
- `updated_at` - timestamp

### Relationships
- A `user` can have many `user_likes` (as liker)
- A `user` can be liked by many `user_likes` (as likee)

### Indexes
- Unique constraint on `users.email`
- Unique constraint on `user_likes` (user_id, person_id) to prevent duplicate likes
- Indexes on foreign keys for performance

### Notes
- The `like_count` in the users table is denormalized for performance reasons
- The `notified` flag is used to track excessive like notifications
- The `is_liked` field in `user_likes` allows for future extension to include 'dislikes' if needed
