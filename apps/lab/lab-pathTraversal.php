<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: /index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Path Traversal - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css?b=6">
<link rel="stylesheet" href="/style/pathTraversal.css?b=6">
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
    <button class="reset-btn" onclick="window.location.href='/lab-pathTraversal.php'">RESET</button>
</nav>

<h2 class="page-title">Path Traversal</h2>

<div class="container">
    <form method="GET">
        <label for="file">File untuk dibaca:</label>
        <select name="file" id="file" autofocus>
            <option value="" <?php if(!isset($_GET['file']) || $_GET['file'] == '') echo 'selected'; ?>>-- pilih file --</option>
            <option value="welcome.txt" <?php if(isset($_GET['file']) && $_GET['file'] == 'welcome.txt') echo 'selected'; ?>>welcome.txt</option>
            <option value="redteam.txt" <?php if(isset($_GET['file']) && $_GET['file'] == 'redteam.txt') echo 'selected'; ?>>redteam.txt</option>
            <option value="blueteam.txt" <?php if(isset($_GET['file']) && $_GET['file'] == 'blueteam.txt') echo 'selected'; ?>>blueteam.txt</option>
        </select>
        <button type="submit">Baca File</button>
    </form>

    <?php
        if (isset($_GET['file']) && $_GET['file'] != '') {
            $file = $_GET['file'];
            $content = @file_get_contents("txt/$file");
            if ($content !== false) {
                echo "<pre>" . htmlspecialchars($content) . "</pre>";
            } else {
                echo "<pre>File not found!</pre>";
            }
        }
    ?>
</div>
</body>
</html>