<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    // Hapus session & cookies
    session_unset();
    session_destroy();

    setcookie('isAdmin', '', time() - 3600, '/', '', false, true);
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    header("Location: /index.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Welcome - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css" />
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #f8fafc;
        margin: 0;
        height: 100%;
        min-height: 100vh;
        overflow-y: auto;
    }

    .navbar {
        background-color: #1f2937;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        margin: 0;
    }

    .navbar h1 {
        margin: 0;
        font-size: 1.25rem;
    }

    .navbar a {
        color: #f8fafc;
        text-decoration: none;
    }
    .navbar a {
        color: #f8fafc;
        text-decoration: none;
        position: relative;
        animation: outlineRGB 3s infinite linear;
    }

    @keyframes outlineRGB {
        0% {
            text-shadow: 0 0 5px red, 0 0 10px orange, 0 0 20px yellow;
        }
        25% {
            text-shadow: 0 0 5px orange, 0 0 10px yellow, 0 0 20px green;
        }
        50% {
            text-shadow: 0 0 5px yellow, 0 0 10px green, 0 0 20px blue;
        }
        75% {
            text-shadow: 0 0 5px green, 0 0 10px blue, 0 0 20px violet;
        }
        100% {
            text-shadow: 0 0 5px blue, 0 0 10px violet, 0 0 20px red;
        }
    }

    .navbar form button {
        background-color: #ef4444;
        color: #f8fafc;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .navbar form button:hover {
        background-color: #dc2626;
    }

    .page-title {
        text-align: center;
        color: #facc15;
        font-size: 1.75rem;
        margin-top: 2rem;
    }

    .container {
        max-width: 800px;
        margin: 1rem auto;
        padding: 2rem;
        background-color: #1e293b;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.4);
        display: flex;            /* Tambah flex */
        flex-direction: column;   /* Stack anak-anaknya ke bawah */
    }

    .container h2 {
        margin: 0 0 1rem 0;
        font-size: 1.25rem;
        line-height: 1.4;
    }

    .container .welcome-text {
        color: #facc15;
        font-weight: 600;
    }

    .container .welcome-name {
        color: #93c5fd;
        font-weight: 600;
    }

    .container p {
        margin: 1rem 0;
        line-height: 1.6;
    }

    .container ul {
        list-style: none;
        padding: 0;
        margin: 1rem 0 0 0;
    }

    .container li {
        margin: 0.5rem 0;
    }

    .container li a {
        display: block;
        background-color: #334155;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        color: #f8fafc;
        text-decoration: none;
        transition: background-color 0.3s, transform 0.2s;
    }

    .container li a:hover {
        background-color: #3b82f6;
        transform: translateX(4px);
    }
</style>
</head>

<body>
<nav class="navbar">
    <h1><a href="/menu.php">Vulnarena Lab</a></h1>
    <form method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
</nav>

<h2 class="page-title">Menu</h2>

<div class="container">
    <h2>
        <span class="welcome-text">Selamat Datang</span>
        <?php
        $user = htmlspecialchars($_SESSION['nickname'] ?? $_SESSION['username']);
        if (!empty($user)) {
            echo ', <span class="welcome-name">' . $user . '</span>!';
        } else {
            echo '!';
        }
        ?>
    </h2>
    <p>Selamat datang di <strong>Vulnarena Lab</strong>! Pilih jenis kerentanan di bawah ini untuk eksplorasi lebih lanjut:</p>

    <ul>
        <li><a href="/lab-xss.php">Cross-Site Scripting (XSS)</a></li>
        <li><a href="/lab-sql.php">SQL Injection</a></li>
        <li><a href="/lab-idor.php?id=<?php echo $_SESSION['user_id']; ?>">Insecure Direct Object Reference (IDOR)</a></li>
        <li><a href="/lab-bac.php">Broken Access Control (BAC)</a></li>
        <li><a href="/lab-bl.php">Business Logic Vulnerability</a></li>
        <li><a href="/lab-commandInjection.php">Command Injection</a></li>
        <li><a href="/lab-pathTraversal.php">Path Traversal</a></li>
    </ul>
</div>

</body>
</html>
