<!DOCTYPE html>
<html>
<head>
    <title>Alert: User Has Exceeded Like Limit</title>
</head>
<body>
    <h1>Alert: User Has Been Liked More Than 50 People</h1>
    
    <h2>User Details:</h2>
    <ul>
        <li><strong>Name:</strong> {{ $userName }}</li>
        <li><strong>Email:</strong> {{ $userEmail }}</li>
        <li><strong>User ID:</strong> {{ $userId }}</li>
        <li><strong>Total Likes:</strong> {{ $userLikeCount }}</li>
    </ul>

    <hr>
    
    <p><em>This is an automated notification from the Tinder-like application system.</em></p>
</body>
</html>