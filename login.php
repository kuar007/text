<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'test');
$message = "";
$profilePicture = "";

$email_mobile = $password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email_mobile = trim($_POST['email_mobile']);
    $password     = trim($_POST['password']);

    if (empty($email_mobile) || empty($password)) {
        $message = "<div class='error'>Both fields are required.</div>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM `village` WHERE email = ? OR mobile = ?");
        $stmt->bind_param("ss", $email_mobile, $email_mobile);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['mobile'] = $user['mobile'];
                $_SESSION['profile_picture'] = $user['profile_picture'];

                    header("Location: dashboard.php");
    exit();

                $message = "<div class='success'>✅ Welcome, " . htmlspecialchars($user['name']) . "!</div>";

                $profilePicture = 'uploads/' . $user['profile_picture'];
            } else {
                $message = "<div class='error'>❌ Incorrect password.</div>";
            }
        } else {
            $message = "<div class='error'>❌ No user found with this email or mobile.</div>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
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
        max-width: 400px;
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

    input[type="text"],
    input[type="password"] {
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
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        margin-top: 20px;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
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

    .profile-img {
        display: block;
        margin: 10px auto;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #007bff;
    }

    .signup-btn {
        background-color: rgb(167, 40, 76);
        margin-top: 10px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>User Login</h2>
        <?php if (!empty($message)) echo $message; ?>

        <?php if (!empty($profilePicture)) : ?>
        <img src="<?php echo htmlspecialchars($profilePicture); ?>" class="profile-img" alt="Profile Picture">
        <?php endif; ?>

        <form method="POST">
            <label>Email or Mobile</label>
            <input type="text" name="email_mobile" value="<?php echo htmlspecialchars($email_mobile); ?>" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
            <div style="text-align: center; margin-top: 15px;">
                <a href="index.php" style="text-decoration: none;">
                    <button type="button" class="signup-btn">New User? Signup</button>
                </a>
            </div>
        </form>
    </div>
</body>

</html>