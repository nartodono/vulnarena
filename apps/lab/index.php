<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None'
    ]);
    session_start();

    if(isset($_SESSION['username'])){
        header("Location: /menu.php");
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" ){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(!empty($username) && !empty($password)){
            require(__DIR__ . '/backend/conn.php');
        
            $query = "SELECT user_id, nickname, username, isadmin, password FROM users WHERE username = '$username' AND password = '$password'";
            $result = $db->query($query);

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();

                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['nickname'] = $row['nickname'];
                setcookie(
                    "isAdmin", 
                    $row['isadmin'], 
                    [
                        'expires' => 0,
                        'path' => '/',
                        'domain' => $_SERVER['HTTP_HOST'],
                        'secure' => true,
                        'httponly' => false,
                        'samesite' => 'None'
                    ]
                );
                header("Location: menu.php");
                exit;
            }
            else{
                echo '<p class="error">Invalid username or password</p>';
            }
        }
        $db->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Vulnarena Lab - Login</title>
<link rel="stylesheet" href="/style/main.css?=2" />
<link rel="stylesheet" href="/style/index.css?a=2" />
</head>
<body>
<nav class="navbar">
    <h1><a href="/index.php">Vulnarena Lab</a></h1>
</nav>

<h2 class="page-title">Masuk Akun</h2>

<div class="content">
    <form method="POST">
        <p>Username</p>
        <input type="text" name="username" placeholder="Masukkan username anda" required>

        <p>Password</p>
        <input type="password" name="password" placeholder="Masukkan password anda" required>

        <button type="submit">Masuk</button>
    </form>
</div>

<div class="demo-credentials">
    <p>Demo Credentials:<br>
    <strong>Username:</strong> john<br>
    <strong>Password:</strong> smith</p>
</div>
</body>
</html>