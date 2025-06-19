<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$name = htmlspecialchars($_SESSION['name']);
$email = htmlspecialchars($_SESSION['email']);
$mobile = htmlspecialchars($_SESSION['mobile']);
$profilePicture = 'uploads/' . htmlspecialchars($_SESSION['profile_picture']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
        }

        .dashboard {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        .info {
            margin-top: 20px;
        }

        .info p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .logout {
            margin-top: 30px;
        }

        .logout a {
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout a:hover {
            background-color: #c0392b;
        }

        .profile-img {
            margin: 20px auto;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h2>Welcome, <?php echo $name; ?>!</h2>

    <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-img">

    <div class="info">
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Mobile:</strong> <?php echo $mobile; ?></p>
    </div>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
