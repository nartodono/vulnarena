<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Command Injection - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css?b=6">
<link rel="stylesheet" href="/style/commandInjection.css?b=6">
</head>
<body>
<nav class="navbar">
    <h1>
        <a href="/menu.php" style="display: flex; align-items: center; gap: 8px; color: #f8fafc; text-decoration: none; animation: outlineRGB 3s infinite linear;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Vulnarena Lab
        </a>
    </h1>
    <form method="GET" action="/lab-commandInjection.php" style="margin: 0;">
        <button type="submit" class="reset-btn">RESET</button>
    </form>
</nav>

<h2 class="page-title">Command Injection</h2>

<div class="container">
    <div class="content">
        <h1>PING Cek</h1>
        <form method="POST">
            <p>Masukka alamat IP atau nama domain:</p>
            <input type="text" name="command" placeholder="contoh: 8.8.8.8 atau example.com">
            <button type="submit">Kirim</button>
        </form>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require(__DIR__ . '/backend/conn.php');

    $cmd = $_POST['command'];

    if(!empty($cmd)){
        $output = shell_exec("ping -c 4 " . $cmd);
        echo "<pre>" . $output . "</pre>";
    } else {
        echo "<pre>Please provide an IP address or domain.</pre>";
    }
}
?>
</body>
</html>