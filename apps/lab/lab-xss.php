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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>XSS - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css?b=6">
<link rel="stylesheet" href="/style/xss.css?b=6">
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

     <form method="GET" action="/lab-xss.php" style="margin: 0;">
        <button type="submit" class="reset-btn">RESET</button>
    </form>
</nav>

<h2 class="page-title">XSS</h2>

<div class="container">

    <div class="search-section">
        <p>Cari disini:</p>
        <form method="GET">
            <input type="text" name="search" placeholder="Cari...">
            <button type="submit">Cari</button>
        </form>
    </div>

    <?php
        if(isset($_GET['search'])) {
            $hasil = $_GET['search'];
            if(empty($hasil)) {
                echo '<div class="result"><strong>Input kosong</strong></div>';
                exit;
            }
            echo '<div class="result"><strong>Hasil pencarian:</strong> ' . $hasil . '</div>';
        }
    ?>

</div>
</body>
</html>