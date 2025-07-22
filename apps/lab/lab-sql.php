<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if(!isset($_SESSION['username'])){
        header("Location: /index.php");
        exit;
    }
    require(__DIR__ . "/backend/conn.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SQL Injection Lab - Vulnarena Lab</title>
<link rel="stylesheet" href="/style/main.css?b=6">
<link rel="stylesheet" href="/style/sql.css?b=6">
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

    <form method="GET" action="/lab-sql.php" style="margin: 0;">
        <button type="submit" class="reset-btn">RESET</button>
    </form>
</nav>

<h2 class="page-title">SQL Injection</h2>

<div class="container">
    <h3>Cari Nama Produk</h3>
    <div class="search-section">
        <form method="GET">
            <input type="text" name="search" placeholder="Cari...">
            <button type="submit">Cari</button>
        </form>
        <p>Coba lakukan pencarian dengan karakter spesial SQL. Contoh: <code>' OR 1=1 #</code></p>
    </div>

    <div class="result">
        <?php
            if(isset($_GET['search'])){
                $item = $_GET['search'];
                $query = "SELECT * FROM products WHERE product_name LIKE '%$item%' AND is_hidden = 0";
            } else {
                $query = "SELECT * FROM products WHERE is_hidden = 0";
            }

            $result = $db->query($query);

            if($result && $result->num_rows > 0){
                echo "<div class='table-container'><table class='custom-table'>";
                echo "<tr><th>Nama Produk</th><th>Stok</th><th>Harga</th></tr>";
                while($row = $result->fetch_assoc()){
                    echo "<tr>
                            <td>" . htmlspecialchars($row['product_name']) . "</td>
                            <td>" . htmlspecialchars($row['product_stock']) . "</td>
                            <td> Rp. " . number_format($row['product_price'], 0, ',', '.') . "</td>
                        </tr>";
                }
                echo "</table></div>";
            } else {
                echo "<p>Produk tidak ditemukan.</p>";
            }
            $db->close();
        ?>
    </div>
</div>
</body>
</html>