# Tinder-like App Backend

## Database Schema

The sql schema is located in `database/schema.sql`

### Tables
- users
- userLikes

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


## Set Up and Installation

1. Install dependencies: `composer install`
2. Set up a local database and update `.env` with your database credentials
3. Run migrations: `php artisan migrate`
4. Generate an app encryption key: `php artisan key:generate`
5. Start the server: `php artisan serve`
