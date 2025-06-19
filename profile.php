<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        .container {
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
            font-family: Arial;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 100px;
            object-fit: cover;
            border: 3px solid #007bff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
    <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" class="profile-img" alt="Profile Picture">
    <p>Username: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
    <a href="logout.php"><button>Logout</button></a>
</div>
</body>
</html>
