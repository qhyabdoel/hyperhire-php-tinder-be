<!DOCTYPE html>
<html>
<head>
    <title>Alert: User Has Exceeded Like Limit</title>
</head>
<body>
    <h1>Alert: User Has Exceeded Like Limit</h1>
    
    <p>A user has exceeded the like limit of 5 people.</p>
    
    <h2>User Details:</h2>
    <ul>
        <li><strong>Name:</strong> {{ $userName }}</li>
        <li><strong>Email:</strong> {{ $userEmail }}</li>
        <li><strong>User ID:</strong> {{ $userId }}</li>
        <li><strong>Total Likes:</strong> {{ $likeCount }}</li>
    </ul>
    
    <p>This user has liked {{ $likeCount }} people, which exceeds the allowed limit of 5 likes.</p>
    
    <p>Please review this user's activity and take appropriate action.</p>
    
    <hr>
    <p><em>This is an automated notification from the Tinder-like application system.</em></p>
</body>
</html>