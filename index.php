<?php
$conn = new mysqli('localhost', 'root', '', 'test');
$message = "";

$name = $username = $email = $mobile = $password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name         = trim($_POST['name']);
    $username     = trim($_POST['username']);
    $email        = trim($_POST['email']);
    $mobile       = trim($_POST['mobile']);
    $password     = trim($_POST['password']);

$profilePicture = $_FILES['profile_picture']['name'];
$imageFileType = strtolower(pathinfo($profilePicture, PATHINFO_EXTENSION));
$valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($imageFileType, $valid_extensions)) {
    $message = "<div class='error'>Only JPG, JPEG, PNG, and GIF files are allowed for profile picture.</div>";
} elseif ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
    $message = "<div class='error'>Profile picture must be less than 2MB.</div>";
} else {
    
    $uniqueFileName = uniqid("img_", true) . "." . $imageFileType;
    $targetDir = "uploads/";
    $targetFile = $targetDir . $uniqueFileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
        
        $finalProfilePicName = $uniqueFileName;
    } else {
        $message = "<div class='error'>Error uploading profile picture.</div>";
    }
}

    
    if (empty($name) || empty($username) || empty($email) || empty($mobile) || empty($password) || empty($profilePicture)) {
        $message = "<div class='error'>All fields including profile picture are required.</div>";
    } elseif (!preg_match("/^[a-zA-Z0-9]{4,20}$/", $username)) {
        $message = "<div class='error'>Username must be 4–20 alphanumeric characters.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error'>Invalid email format.</div>";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $message = "<div class='error'>Mobile number must be exactly 10 digits.</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='error'>Password must be at least 6 characters long.</div>";
    } elseif (!in_array($imageFileType, $valid_extensions)) {
        $message = "<div class='error'>Only JPG, JPEG, PNG, and GIF files are allowed for profile picture.</div>";
    } elseif ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
        $message = "<div class='error'>Profile picture must be less than 2MB.</div>";
    } else {
       
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile);

        
        $check = $conn->prepare("SELECT * FROM `village` WHERE email = ? OR mobile = ?");
        $check->bind_param("ss", $email, $mobile);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "<div class='error'>❌ Email or Mobile already registered.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO `village`(`name`, `username`, `email`, `mobile`, `password`, `profile_picture`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $username, $email, $mobile, $hashed_password, $finalProfilePicName);

            if ($stmt->execute()) {
                $message = "<div class='success'>✅ Signup successful!</div>";
                $name = $username = $email = $mobile = $password = "";
            } else {
                $message = "<div class='error'>❌ Error: " . $stmt->error . "</div>";
            }
        }
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Signup Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .container {
            width: 100%;
            max-width: 450px;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 12px;
            display: block;
            color: #555;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            color: green;
            font-weight: bold;
            background: #e6ffed;
            border: 1px solid #28a745;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .error {
            color: red;
            font-weight: bold;
            background: #ffe6e6;
            border: 1px solid #dc3545;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup Form</h2>
        <?php if (!empty($message)) echo $message; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Full Name</label>
            <input type="text" name="name" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Mobile Number</label>
            <input type="text" name="mobile" required>

            <label>Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Sign Up</button>
            <div style="text-align: center; margin-top: 15px;">
                <a href="login.php" style="text-decoration: none;">
                    <button type="button" style="background-color: #28a745;">Already registered? Login</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>
